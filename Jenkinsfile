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
        SONAR_URL = "http://my-sonarqube-sonarqube.sonarqube.svc.cluster.local:9000"
        IMAGE_NAME = "event-promotion-website"
        NAMESPACE = "your-roll-no-namespace" // Change to your actual namespace
        // UPDATE THIS ID BELOW to the correct one from your college
        CREDS_ID = "nexus" 
    }
    stages {
        stage('Build') {
            steps { echo 'Building application...' }
        }
        stage('Analyze') {
            steps { echo "Analyzing code at ${SONAR_URL}" }
        }
        stage('Package') {
            steps {
                container('docker') {
                    script {
                        sh "docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ."
                    }
                }
            }
        }
        stage('Push to Registry') {
            steps {
                container('docker') {
                    withCredentials([usernamePassword(credentialsId: "${CREDS_ID}", passwordVariable: 'NEXUS_PWD', usernameVariable: 'NEXUS_USR')]) {
                        script {
                            sh "docker login -u ${NEXUS_USR} -p ${NEXUS_PWD} http://${REGISTRY}"
                            sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
                        }
                    }
                }
            }
        }
        stage('Deploy to Kubernetes') {
            steps {
                script {
                    sh "kubectl apply -f k8s/ -n ${NAMESPACE}"
                    sh "kubectl set image deployment/${IMAGE_NAME} event-promotion-container=${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} -n ${NAMESPACE}"
                }
            }
        }
    }
}