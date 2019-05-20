pipeline {
    agent none
    stages {
        stage ('build') {
            agent {
                dockerfile {
                    filename 'docker/Dockerfile.jenkins'
                    dir 'build'
                }
            }
            steps {
                echo 'Building image'
            }
        }
    }
}
