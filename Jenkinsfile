pipeline {
  agent any
  stages {
    stage('echo') {
      steps {
        slackSend(channel: 'lms', color: 'blue', sendAsText: true, attachments: 'Haha', blocks: 'Block')
      }
    }

  }
}