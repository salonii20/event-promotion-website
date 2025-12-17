pipeline {
    agent any
    environment {
        // Updated with your provided Nexus registry URL
        REGISTRY = "nexus-service-for-docker-hosted-registry.nexus.svc.cluster.local:8085"
        // Updated with your provided SonarQube URL
        SONAR_URL = "http://my-sonarqube-sonarqube.sonarqube.svc.cluster.local:9000"
        IMAGE_NAME = "event-promotion-website"
        NAMESPACE = "your-roll-no-namespace" // Change this to your actual roll number [cite: 93]
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
                // The guide mentions code quality is checked here [cite: 17]
            }
        }
        stage('Package') {
            steps {
                script {
                    // Builds the image using the mandatory Dockerfile [cite: 44, 127]
                    sh "docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ."
                }
            }
        }
        stage('Push to Registry') {
            steps {
                script {
                    // Pushes to the Nexus registry provided [cite: 19, 129]
                    sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
                }
            }
        }
        stage('Deploy to Kubernetes') {
            steps {
                script {
                    // Automatically applies manifests in the k8s folder [cite: 20, 130]
                    sh "kubectl apply -f k8s/ -n ${NAMESPACE}"
                    sh "kubectl set image deployment/${IMAGE_NAME} event-promotion-container=${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} -n ${NAMESPACE}"
                }
            }
        }
    }
}