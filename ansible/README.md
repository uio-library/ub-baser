## Deploying with Ansible

### Setup SSH connection as the deploy user

Create a SSH key pair:

    $ ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa.ub-baser-deploy -C "Deploy key for ub-baser (YOUR NAME)"

Please protect it with a strong passphrase.

Copy the public key (`~/.ssh/id_rsa.ub-baser-deploy.pub`),
login to the server and append the public key to `/home/deploy/.ssh/authorized_keys`.

To test if the key is working, try this:

    $ ansible -m shell -a whoami prod
    ub-baser.uio.no | CHANGED | rc=0 >>
    deploy

The last line is the result from running the `whoami` command remotely using Ansible.
It should say "deploy".

In case of connections problems, check that the filename of your key matches the one in `hosts.yml`.

### Deploying

To deploy, run:

    $ ansible-playbook deploy.yml

In case of problems, add `-vvvv` to get very verbose output.
