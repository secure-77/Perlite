FROM nginx:stable

MAINTAINER sec77 https://github.com/secure-77/Perlite

RUN rm /etc/nginx/conf.d/default.conf 
COPY ./config/ /etc/nginx/conf.d/

EXPOSE 80
