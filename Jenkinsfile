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
        SONAR_URL = "http://my-sonarqube-sonarqube.sonarqube.svc.cluster.local:9000"
        IMAGE_NAME = "event-promotion-website"
        NAMESPACE = "your-roll-no-namespace" 
    }
    stages {
        stage('Build') {
            steps {
                echo 'Building and preparing application code...'
            }
        }
        stage('Analyze') {
            steps {
                echo "Performing SonarQube quality analysis at ${SONAR_URL}"
            }
        }
        stage('Package') {
            steps {
                container('docker') {
                    script {
                        // Builds the image using the Dockerfile in the root
                        sh "docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ."
                    }
                }
            }
        }
        stage('Push to Registry') {
            steps {
                container('docker') {
                    script {
                        // Pushes the image to the Nexus registry
                        sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
                    }
                }
            }
        }
        stage('Deploy to Kubernetes') {
            steps {
                script {
                    // Applies manifests from the k8s folder
                    sh "kubectl apply -f k8s/ -n ${NAMESPACE}"
                    sh "kubectl set image deployment/${IMAGE_NAME} event-promotion-container=${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} -n ${NAMESPACE}"
                }
            }
        }
    }
}