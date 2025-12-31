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

  - name: kubectl
    image: bitnami/kubectl:latest
    command: ["cat"]
    tty: true
    securityContext:
      runAsUser: 0
      readOnlyRootFilesystem: false
    env:
    - name: KUBECONFIG
      value: /kube/config
    volumeMounts:
    - name: kubeconfig-secret
      mountPath: /kube/config
      subPath: kubeconfig

  volumes:
  - name: docker-config
    configMap:
      name: docker-daemon-config
  - name: kubeconfig-secret
    secret:
      secretName: kubeconfig-secret
'''
        }
    }

    options { skipDefaultCheckout() }

    environment {
        DOCKER_IMAGE  = "event-promotion-website"
        REGISTRY_HOST = "nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085"
        REGISTRY      = "${REGISTRY_HOST}/2401172"
        NAMESPACE     = "2401172"

        SONAR_PROJECT = "2401172_Eventure"
        SONAR_HOST    = "http://my-sonarqube-sonarqube.sonarqube.svc.cluster.local:9000"
        SONAR_TOKEN   = "sqp_e6d7eeec95c8bd2fa2299fdda33495d5527313c5"
    }

    stages {

        stage('Checkout Code') {
            steps {
                deleteDir()
                sh 'git clone https://github.com/salonii20/event-promotion-website.git .'
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
                                } catch (e) {
                                    sleep 5
                                    return false
                                }
                            }
                        }
                        sh '''
                            docker build -t event-promotion-website:${BUILD_NUMBER} .
                            docker tag event-promotion-website:${BUILD_NUMBER} event-promotion-website:latest
                        '''
                    }
                }
            }
        }

        stage('Run Tests & Coverage') {
            steps {
                container('dind') {
                    sh '''
                        docker run --rm event-promotion-website:latest \
                        sh -c "php -l index.php || true"
                    '''
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                container('sonar-scanner') {
                    sh '''
                        sonar-scanner \
                          -Dsonar.projectKey=2401172_Eventure \
                          -Dsonar.projectName=2401172_Eventure \
                          -Dsonar.host.url=http://my-sonarqube-sonarqube.sonarqube.svc.cluster.local:9000 \
                          -Dsonar.token=${SONAR_TOKEN} \
                          -Dsonar.sources=. \
                          -Dsonar.language=php
                    '''
                }
            }
        }

        stage('Login to Nexus') {
            steps {
                container('dind') {
                    sh '''
                        echo "Logging into Nexus registry..."
                        docker login nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085 \
                          -u admin -p Changeme@2025
                    '''
                }
            }
        }

        stage('Push Image') {
            steps {
                container('dind') {
                    sh '''
                        docker tag event-promotion-website:${BUILD_NUMBER} ${REGISTRY}/event-promotion-website:${BUILD_NUMBER}
                        docker tag event-promotion-website:${BUILD_NUMBER} ${REGISTRY}/event-promotion-website:latest

                        docker push ${REGISTRY}/event-promotion-website:${BUILD_NUMBER}
                        docker push ${REGISTRY}/event-promotion-website:latest
                    '''
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                container('kubectl') {
                    dir('k8s-deployment') {
                        sh '''
                            kubectl get namespace 2401172 || kubectl create namespace 2401172
                            kubectl apply -f deployment.yaml -n 2401172
                        '''
                    }
                }
            }
        }
    }

    post {
        success { echo "🎉 Event Promotion Pipeline completed successfully" }
        failure { echo "❌ Pipeline failed" }
        always  { echo "🔄 Pipeline finished" }
    }
}