FROM rabbitmq:3.7.4-management

RUN apt-get update && apt-get install wget unzip -y \
    && wget https://dl.bintray.com/rabbitmq/community-plugins/3.7.x/rabbitmq_delayed_message_exchange/rabbitmq_delayed_message_exchange-20171201-3.7.x.zip -P /plugins \
    && cd /plugins && unzip rabbitmq_delayed_message_exchange-20171201-3.7.x.zip \
    && rabbitmq-plugins enable rabbitmq_delayed_message_exchange