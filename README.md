# Mail Admin
Simple Symfony app that maintains an MySQL database containing domains, mailboxes and aliases for Dovecot and Postfix.

## Creating a new domain

1. Create domain in mailadmin
2. Add SSL cert to dovecot (`/etc/dovecot/dovecot.conf`):
```
local_name mail.<domain> {
  ssl_cert = </etc/letsencrypt/live/<domain>/fullchain.pem
  ssl_key = </etc/letsencrypt/live/<domain>/privkey.pem
}
local_name <domain> {
  ssl_cert = </etc/letsencrypt/live/<domain>/fullchain.pem
  ssl_key = </etc/letsencrypt/live/<domain>/privkey.pem
}  
```
3. Add SSL cert to Postfix (`/etc/postfix/vmail_ssl.map`) and run postmap
```
mail.<domain> /etc/letsencrypt/live/<domain>/privkey.pem /etc/letsencrypt/live/<domain>/fullchain.pem
<domain> /etc/letsencrypt/live/<domain>/privkey.pem /etc/letsencrypt/live/<domain>/fullchain.pem
``` 
4. Add DKIM public key record to DNS (located in `/var/lib/rspamd/dkim/`)
5. Add DMARC and SPF record to DNS:
```
_dmarc  v=DMARC1; p=reject; sp=reject; pct=100; ri=86400
@       v=spf1 mx -all
```
