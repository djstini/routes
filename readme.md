# ROUTES

This Repo contains "API Routes" hosted on my Hetzner Server under the /routes/ directory.

# DNS

ENDPOINTS: 
- GET: /routes/dns

## GET: /routes/dns

**Erforderliche Parameter**

Alle Parameter müssen Base64 encoded sein.

 - ipv4
 - ipv6
 - secret

 **Erforderliche Config**

 Im Verzeichnis /dns/ hat sich auf dem Server eine `.config.php` Datei zu finden.
 In dieser müssen folgende Konstanten Definiert werden:

 - DNS_AUTH_API_TOKEN    *the token for the hetzner dns auth api*
 - DNS_SECRET    *the secret given via the request*
 - DNS_ZONE_ID_NONAGON_DEV *the zone ID for the hetzner zonefile*
 **Sites**

 Der DNS Service läuft über meherer Sites, diese werden im verzeichnis /sites/ definiert.
 Alle Sites extenden die abstrakte Klasse `abstract-class-subdomain-handler.php` welche die Kernfunktionen umsetzt.
 In den "erweiternden" Klassen werden speziefische Configs durchgeführt.

 Als interface muss hier die `update_records` funktion implementiert werden mit welcher dann der DNS Update versendet wird.

 Da jede Site noch erweiternde Config benötigt hier weitere "Site-speziefische" Konstanten für die `/dns/.config.php`

 - class-interface-nonagon-dev.php -> interface.nonagon.dev
 - class-jelly-nonagon-dev.php -> jelly.nonagon.dev
 - class-tandoor-nonagon-dev.php -> tandoor.nonagon.dev
 - class-matrix-nonagon-dev.php -> matrix.nonagon.dev

 ## README:
 I am not so sure if the architectural divide between installs is a good idea.. i thought it would maybe be necessary for more complex configs,, but i dont know if that will ever be required.. but oh well. it should work anyways.
 
