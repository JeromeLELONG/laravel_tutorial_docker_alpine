FROM mysql:8
#ENV MYSQL_ROOT_PASSWORD root
#ENV MYSQL_DATABASE siscolidentifiant
COPY init.sql /docker-entrypoint-initdb.d/init.sql
COPY my.cnf /etc/mysql/my.cnf
WORKDIR /docker-entrypoint-initdb.d/


RUN mkdir -p /etc/mysql/conf.d/
RUN mkdir /etc/mysql/mysql.conf.d
RUN mkdir /var/lib/mysql-files
RUN echo "[mysql]" | tee /etc/mysql/conf.d/mysql.cnf

CMD ["--character-set-server=latin1", "--character-set-filesystem=latin1", "--collation-server=latin1_swedish_ci"]
#RUN /etc/init.d/mysql start
#RUN  mysql -u root -p${MYSQL_ROOT_PASSWORD}   < /docker-entrypoint-initdb.d/init.sql