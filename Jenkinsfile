pipeline {
    agent {
        kubernetes {
            yaml """
apiVersion: v1
kind: Pod
spec:
  containers:
  - name: docker
    image: docker:dind
    securityContext:
      privileged: true
    command:
    - dockerd-entrypoint.sh
    - --insecure-registry=nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085
    volumeMounts:
    - name: dind-storage
      mountPath: /var/lib/docker
  volumes:
  - name: dind-storage
    emptyDir: {}
"""
        }
    }
    environment {
        REGISTRY = "nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085"
        IMAGE_NAME = "event-promotion-website"
        // Try '2401172' first. If it says NotFound, change this to 'default'
        NAMESPACE = "default" 
        CREDS_ID = "nexus-credentials"
    }
    stages {
        stage('Package') {
            steps {
                container('docker') {
                    sh "docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ."
                }
            }
        }
        stage('Push to Registry') {
            steps {
                container('docker') {
                    withCredentials([usernamePassword(credentialsId: "${CREDS_ID}", passwordVariable: 'PWD', usernameVariable: 'USR')]) {
                        sh "docker login -u student -p ${PWD} http://${REGISTRY}"
                        sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
                    }
                }
            }
        }
        stage('Deploy to Kubernetes') {
            steps {
                container('docker') {
                    script {
                        sh """
                            wget https://dl.k8s.io/release/v1.28.0/bin/linux/amd64/kubectl
                            chmod +x kubectl
                            ./kubectl apply -f k8s/ -n ${NAMESPACE}
                            ./kubectl set image deployment/event-promotion-website event-promotion-container=${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} -n ${NAMESPACE}
                        """
                    }
                }
            }
        }
    }
}