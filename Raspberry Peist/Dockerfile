FROM gcc:latest AS egg-builder

COPY egg-cracker.c .
RUN gcc -o egg-cracker egg-cracker.c


FROM agners/archlinuxarm-arm64v8:latest

RUN pacman -Sy archlinux-keyring --noconfirm \
 && pacman -Syu base openssh python python-pip php --noconfirm
COPY ssh/ /etc/ssh
RUN chmod 600 /etc/ssh/*

RUN useradd -m intern                                \
 && useradd -m master-baker                          \
 && echo "intern:chinchilla" | chpasswd              \
 && ln -s /dev/null /home/intern/.bash_history       \
 && ln -s /dev/null /home/master-baker/.bash_history \
 && ln -s /dev/null /root/.bash_history

WORKDIR /home/master-baker
COPY keys/ ./.ssh/
COPY local-app .
RUN chown -R master-baker:master-baker . && chmod 600 ./.ssh/*

COPY --from=egg-builder egg-cracker /home/master-baker/.egg-cracker
COPY egg /home/master-baker/.egg
RUN chmod u+s /home/master-baker/.egg-cracker && chmod 000 /home/master-baker/.egg

COPY user.txt /home/intern/
COPY master.txt /home/master-baker/
COPY root.txt /root/

RUN useradd -m flask

COPY ctf-management/ /home/flask/ctf-management
RUN chown flask:flask -R /home/flask
WORKDIR /home/flask/ctf-management

USER flask
RUN pip install -r requirements.txt --no-warn-script-location

USER root
EXPOSE 22
EXPOSE 5000
COPY start.sh /srv/
ENTRYPOINT ["/srv/start.sh"]