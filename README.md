Программа на PHP для сброса паролей пользователей при подключении к Windows Server по протоколу LDAPS.

Важно!: при соединении с AD по протоколу LDAP (порт 389) не работает функция php для смены пароля пользователю AD. Решением является переход на SSL-версию протокола — LDAPS (порт 636). Все действия по созданию сертификата производились на том же Windows Server, где и развёрнута служба каталогов Active Directory.
Для выпуска сертификата необходимо установить следующие роли:
- Служба сертификатов Active Directory, в частности, важную составляющую - "Службы регистрации в центре сертификации через Интернет".

Дополнительная литература по развёртыванию центра сертификации:
https://habr.com/ru/sandbox/36457/
https://abuzov.com/active-directory-certificate-services/

После того, как развёрнут центр сертификации необходимо выпустить сертификат для сервера. Однако перед этим необходимо сконфигурировать файл "request.inf" (файл прилагается) и выполнить команду от имени администратора в CMD: certreq -new request.inf request.req.

После чего необходимо зайти на веб-интерефейс центра сертификации - http://localhost/certsrv/certrqxt.asp/ и вставить в первое окно код из файла "request.req".

Последним этапом необходимо импортировать сертификат. Запустить оснастку MMC и выполнить следующиее:
Certificates — Add — Computer Account — Next — Local Computer — Finish — OK. Certificates (local computer) — Personal — Certificates (правой кнопкой) — All Tasks — Import.

Проверка: Winkey+R — ldp; Connection — Connect… Server — это FQDN нашего контроллера домена, например, dc.company.org. Port: 636, галочка SSL. OK. «Cannot open connection» означает, что цель не достигнута. Положительный результат будет выглядеть как много строк в правой половине окошка, включая «Established connection to dc.company.org». Теперь можно указывать ldaps://dc.company.org в качестве url ldap-сервера в информационной системе.

Последующие действия проводятся исключительно на сервере, где будет развёрнут сервис для сброса пароля. В качестве примера, взята РЕД ОС, на которой установлены следующие компоненты:
- nginx
- php php-fpm php-common php-ldap php-mbstring
- openldap openldap-clients

Для корректной работы ldap клиента необходимо импортировать сертификат, сфомированный ранее для сервера LDAPS и положить его по пути: /etc/openldap/certs/
Отредактировать файл по пути: /etc/openldap/ldap.conf, вставив в конце следующее:

TLS_REQCERT never
TLS_CACERT /etc/openldap/certs/<name certificate.pem>
