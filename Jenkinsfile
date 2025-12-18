pipeline {
    agent {
        kubernetes {
            yaml '''
apiVersion: v1
kind: Pod
spec:
  containers:
  - name: dind
    image: docker:dind
    securityContext:
      privileged: true
    env:
    - name: DOCKER_TLS_CERTDIR
      value: ""
  - name: sonar-scanner
    image: sonarsource/sonar-scanner-cli
    command: ["cat"]
    tty: true
  - name: kubectl
    image: bitnami/kubectl:latest
    command: ["sleep", "infinity"]
'''
        }
    }

    options { skipDefaultCheckout() }

    environment {
        DOCKER_IMAGE  = "event-promotion-website"
        SONAR_TOKEN   = "sqp_e6d7eeec95c8bd2fa2299fdda33495d5527313c5"
        REGISTRY_HOST = "nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085"
        REGISTRY      = "${REGISTRY_HOST}/2401172"
        NAMESPACE     = "default" 
    }

    stages {
        stage('Checkout Code') {
            steps {
                deleteDir()
                sh "git clone https://github.com/salonii20/event-promotion-website.git ."
            }
        }

        stage('Build Docker Image') {
            steps {
                container('dind') {
                    script {
                        // FIX: Configure insecure registry before build
                        sh """
                            mkdir -p /etc/docker
                            echo '{"insecure-registries": ["${REGISTRY_HOST}"]}' > /etc/docker/daemon.json
                            # Kill and restart dockerd to apply the configuration
                            pkill dockerd || true
                            dockerd --host=unix:///var/run/docker.sock --host=tcp://0.0.0.0:2375 &
                        """
                        
                        timeout(time: 1, unit: 'MINUTES') {
                            waitUntil {
                                try {
                                    sh 'docker info >/dev/null 2>&1'
                                    return true
                                } catch (Exception e) {
                                    sleep 5
                                    return false
                                }
                            }
                        }
                        sh "docker build --network=host -t ${DOCKER_IMAGE}:${BUILD_NUMBER} ."
                    }
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                container('sonar-scanner') {
                    sh """
                        sonar-scanner \
                          -Dsonar.projectKey=2401172_Eventure \
                          -Dsonar.host.url=http://my-sonarqube-sonarqube.sonarqube.svc.cluster.local:9000 \
                          -Dsonar.token=${SONAR_TOKEN} \
                          -Dsonar.sources=.
                    """
                }
            }
        }

        stage('Push Image') {
            steps {
                container('dind') {
                    sh "docker login ${REGISTRY_HOST} -u admin -p Changeme@2025"
                    sh "docker tag ${DOCKER_IMAGE}:${BUILD_NUMBER} ${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER}"
                    sh "docker push ${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER}"
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                container('kubectl') {
                    dir('k8s-deployment') {
                        sh """
                            kubectl apply -f deployment.yaml -n ${NAMESPACE}
                            kubectl set image deployment/event-promotion-website event-promotion-container=${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER} -n ${NAMESPACE}
                        """
                    }
                }
            }
        }
    }

    post {
        success { echo "🎉 Pipeline GREEN! Final deployment successful." }
    }
}