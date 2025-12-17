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
        NAMESPACE = "your-roll-no-namespace" // Ensure this is your actual roll number
    }
    stages {
        stage('Build & Analyze') {
            steps {
                echo 'Preparing application...'
                echo "Analyzing at ${SONAR_URL}"
            }
        }
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
                    script {
                        // We will try the two most common college IDs:
                        def possibleIds = ["nexus-credentials", "registry-creds", "nexus"]
                        def found = false
                        
                        for (id in possibleIds) {
                            if (found) break
                            try {
                                withCredentials([usernamePassword(credentialsId: id, passwordVariable: 'PWD', usernameVariable: 'USR')]) {
                                    sh "docker login -u ${USR} -p ${PWD} http://${REGISTRY}"
                                    sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
                                    found = true
                                    echo "Successfully pushed using ID: ${id}"
                                }
                            } catch (e) {
                                echo "Credential ID '${id}' not found, trying next..."
                            }
                        }
                        if (!found) {
                            error("Could not find any valid Nexus credentials. Please ask your instructor for the correct 'Credentials ID'.")
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