Create an SSH key `~/.ssh/id_rsa.ub-baser-deploy` and transfer the
public key to the server(s).

Locally, create a hosts file in e.g.

    $HOME/.ansible/hosts.yml

with something like:

    ub_baser:
      hosts:
        ub-baser.uio.no:
      vars:
        ansible_user: deploy
        ansible_ssh_private_key_file: ~/.ssh/id_rsa.ub-baser-deploy

Make sure it's referred to in `~/.ansible.cfg`:

    inventory      = /Users/USERNAME/.ansible/hosts.yml

Then test it:

    $ ansible -i ~/.ansible/hosts.yml -m shell -a whoami ub_baser
    ub-baser.uio.no | CHANGED | rc=0 >>
    deploy

If successful, go ahead and deploy

    $ ansible-playbook deploy.yml

In case of problems, add `-vvvv` to get very verbose output.
