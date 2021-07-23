## Deploying with Ansible

### Setup SSH connection as the deploy user

Create a SSH key pair:

    $ ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa.ub-baser-deploy -C "Deploy key for ub-baser (YOUR NAME)"

Please protect it with a strong passphrase.

Copy the contents of the public key file (`~/.ssh/id_rsa.ub-baser-deploy.pub`),
login to the server and append the contents to a new line in `/home/deploy/.ssh/authorized_keys`.

Then check if you can login using `ssh -i ~/.ssh/id_rsa.ub-baser-deploy deploy@ub-baser.uio.no`.

If that succeeds, you can proceed to test if you can run `ansible` (from this directory):

    $ ansible -m shell -a whoami prod
    ub-baser.uio.no | CHANGED | rc=0 >>
    deploy

The last line is the result from running the `whoami` command remotely using Ansible.
It should say "deploy".

In case of connections problems, check that the filename of your key matches the one in `hosts.yml`.

### Deploying

To deploy:

1. Start by pushing the code to GitHub (`git push`)
2. Wait for Travis tests to complete: https://travis-ci.org/scriptotek/ub-baser/builds
3. Ideally a deploy would automatically happen at this point, but it doesn't. So you need to manually run the following:

        $ ansible-playbook deploy.yml

In case of problems, add `-vvvv` to the above command get very verbose output.

## Troubleshooting: If the app fails to start

Check if container is running:

    $ ansible prod -m shell -a 'docker ps'

Check logs:

    $ ansible prod -m shell -a 'docker logs $(docker ps -q)'

Re-create the container:

    $ ansible prod -m shell -a 'docker service update --force ub-baser_app'
