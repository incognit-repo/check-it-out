while
  inotifywait -e modify /app/supervisord.conf;
do
  cp /app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf;
  supervisorctl update;
done
