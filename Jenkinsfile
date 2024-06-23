pipeline {
    agent any

    stages {
        stage('Checkout Code') {
            steps {
                git 'https://github.com/CarmelAdk/ulearn'
                echo 'Git Checkout Completed'
            }
        }
        
        stage('Build Docker Image') {
            steps {
                sh 'docker build --file .docker/app/Dockerfile -t carmeladikin/lms:2.$BUILD_NUMBER .'
                echo 'Build Image Completed'
            }
        }
        
        stage('Docker Hub Authentication')  {
            steps {
                withCredentials([usernamePassword(credentialsId: 'dockerhub', usernameVariable: 'DOCKERHUB_USER', passwordVariable: 'DOCKERHUB_PASS')]) {
                    sh 'echo $DOCKERHUB_PASS | docker login -u $DOCKERHUB_USER --password-stdin'
                }
                echo 'Login Completed'
            }
        }
        
        stage('Push Image') {
            steps {
                sh 'docker push carmeladikin/lms:2.$BUILD_NUMBER'
                echo 'Push Image Completed'
            }
            post {
              success {
                  slackSend channel: 'lms', 
                  message: 'L\'image Docker a bien été publiée sur le DockerHub. https://hub.docker.com/repository/docker/carmeladikin/lms/general',
                  tokenCredentialId: 'slackBotToken'
              }
              failure {
                  slackSend channel: 'lms', 
                  message: 'Un problème est survenu lors de la publication.',
                  tokenCredentialId: 'slackBotToken'
              }
          }
        }

        stage('Deploy to test environment') {
            steps {
                sh "sed -i -E 's|(APP_URL=).*|APP_URL=http://localhost:8000|' .env"
                sh "composer update"
                sshagent(['test-server']) {
                    sh '''
                        ssh -tt -o StrictHostKeyChecking=no carmel@localhost "cd ulearn; git pull; sed -i -E 's|(APP_VERSION=).*|APP_VERSION=2.$BUILD_NUMBER|' .env; docker compose up -d; docker compose exec app composer update; docker compose exec app php artisan migrate; docker compose exec app php artisan db:seed"
                    '''
                }
                echo 'Deployed to test environment'
                sh 'php artisan dusk:install; rm -f tests/Browser/ExampleTest.php'
                sh 'vendor/bin/phpunit tests/Browser'
                echo 'App tested !'
            }
            post {
                success {
                  slackSend channel: 'lms', 
                  message: 'Les tests ont tous réussi.',
                  tokenCredentialId: 'slackBotToken'
                }
                failure {
                  slackSend channel: 'lms', 
                  message: 'Il y a un problème lors du déploiement en staging ou lors des tests.',
                  tokenCredentialId: 'slackBotToken'
                }
            }
        }

        stage('Deploy to prod environment') {
            steps {
                sshagent(['prod-server']) {
                    sh '''
                        ssh -tt -o StrictHostKeyChecking=no carmel-prod@ulearn.southafricanorth.cloudapp.azure.com \
                        "cd ulearn; git pull; \
                        sed -i -E 's|(APP_VERSION=).*|APP_VERSION=2.$BUILD_NUMBER|' .env; docker compose up -d; \
                        docker compose exec app composer update; \
                        docker compose exec app php artisan migrate; docker compose exec app php artisan db:seed"
                    '''
                }
            }
            post {
                success {
                  slackSend channel: 'lms', 
                  message: 'Déploiement réussi. http://ulearn.southafricanorth.cloudapp.azure.com:8000/',
                  tokenCredentialId: 'slackBotToken'
                }
                failure {
                  slackSend channel: 'lms', 
                  message: 'Un problème est survenu lors du déploiement.',
                  tokenCredentialId: 'slackBotToken'
                }
            }
        }
    }

    post {
        always {
            sh 'docker logout'
            sh 'docker stop $(docker ps -aq)'
            sh 'docker rm $(docker ps -aq)'
            sh 'docker rmi -f $(docker images -aq)'
        }
    }
}



