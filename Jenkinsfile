pipeline {
	agent any

	stages {
		stage('Build image') {
			steps {
				sh 'docker build -t msarmadqadeer/socialspace:latest .'
			}
		}
		stage('Push image to Docker Hub') {
			steps {
				sh 'docker login -u $DOCKER_HUB_USERNAME -p $DOCKER_HUB_PASSWORD'
				sh 'docker push msarmadqadeer/socialspace:latest'
			}
		}
		stage('Start containers') {
			steps {
				sh 'docker rm -f socialspace'
				sh 'docker-compose up -d'
			}
		}
	}

	post {
		always {
			sh 'docker logout'
		}
	}
}