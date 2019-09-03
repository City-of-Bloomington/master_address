# Helm Chart for Kubernetes Deployment

This helm chart deploys the docker container into a Kubernetes cluster.  This assumes you are using an external database (not declared in this docker image).

You will need to provide your own values.yml with all custom (secret) values for your deployment hosting.  Once you've got that, you should be able to use Helm to deploy:

```bash
helm install . -values /path/to/your/secret/values.yml
```
