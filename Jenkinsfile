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
    volumeMounts:
    - name: docker-config
      mountPath: /etc/docker/daemon.json
      subPath: daemon.json
  - name: sonar-scanner
    image: sonarsource/sonar-scanner-cli
    command: ["cat"]
    tty: true
  volumes:
  - name: docker-config
    configMap:
      name: docker-daemon-config
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
        CREDS_ID      = "nexus-credentials"
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
                        sh "docker build -t ${DOCKER_IMAGE}:${BUILD_NUMBER} ."
                    }
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                container('sonar-scanner') {
                    sh """
                        sleep 10
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
                    withCredentials([usernamePassword(credentialsId: "${CREDS_ID}", passwordVariable: 'PWD', usernameVariable: 'USR')]) {
                        sh "docker login ${REGISTRY_HOST} -u ${USR} -p ${PWD}"
                        sh "docker tag ${DOCKER_IMAGE}:${BUILD_NUMBER} ${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER}"
                        sh "docker push ${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER}"
                    }
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                container('sonar-scanner') {
                    dir('k8s-deployment') {
                        sh """
                            if [ ! -f ./kubectl ]; then
                                curl -LO "https://dl.k8s.io/release/\$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
                                chmod +x kubectl
                            fi
                            ./kubectl apply -f deployment.yaml -n ${NAMESPACE}
                            ./kubectl set image deployment/event-promotion-website event-promotion-container=${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER} -n ${NAMESPACE}
                        """
                    }
                }
            }
        }
    }

    post {
        success { echo "🎉 SUCCESS! Build #${BUILD_NUMBER} is GREEN." }
    }
}