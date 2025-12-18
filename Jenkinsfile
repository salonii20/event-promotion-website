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

    environment {
        DOCKER_IMAGE  = "event-promotion-website"
        SONAR_TOKEN   = "sqp_f42fc7b9e4433f6f08040c3f2303f1e5cc5524c1"
        REGISTRY_HOST = "nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085"
        REGISTRY      = "${REGISTRY_HOST}"
        NAMESPACE     = "default"
        CREDS_ID      = "nexus-credentials"
    }

    stages {
        stage('Package') {
            steps {
                container('dind') {
                    script {
                        sh "docker build -t ${DOCKER_IMAGE}:${BUILD_NUMBER} ."
                    }
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                container('sonar-scanner') {
                    sh """
                        sonar-scanner \
                          -Dsonar.projectKey=${DOCKER_IMAGE} \
                          -Dsonar.sources=. \
                          -Dsonar.host.url=http://my-sonarqube-sonarqube.sonarqube.svc.cluster.local:9000 \
                          -Dsonar.token=${SONAR_TOKEN}
                    """
                }
            }
        }

        stage('Push to Registry') {
            steps {
                container('dind') {
                    withCredentials([usernamePassword(credentialsId: "${CREDS_ID}", passwordVariable: 'PWD', usernameVariable: 'USR')]) {
                        sh "docker login -u ${USR} -p ${PWD} http://${REGISTRY_HOST}"
                        sh "docker tag ${DOCKER_IMAGE}:${BUILD_NUMBER} ${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER}"
                        sh "docker push ${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER}"
                    }
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                container('kubectl') {
                    // Applies manifests from standard k8s/ folder
                    sh "kubectl apply -f k8s/ -n ${NAMESPACE}"
                    // Updates the deployment with the exact build number
                    sh "kubectl set image deployment/event-promotion-website event-promotion-container=${REGISTRY}/${DOCKER_IMAGE}:${BUILD_NUMBER} -n ${NAMESPACE}"
                }
            }
        }
    }

    post {
        success { echo "🎉 Pipeline GREEN! Application deployed to ${NAMESPACE}" }
    }
}