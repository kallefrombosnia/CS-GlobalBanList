###################
CS-GlobalBanList API
###################

CS-GlobalBanList API je ideja da se stvori centralna ban lista citera koji su pali na rechekeru ili manuelnom banu administracije.
Projekt je napravljen u PHP, a za framework je koristen Codeigniter 3.0 i rest server library da bi se olaksao rad.
Zbog nemogucnosti amxx plugina da salje POST requeste, simuliran je POST uz pomoc GET request-a.

**************************
Changelog and New Features
**************************

Zadnja verzija je v1,dok je planirana mogucnost daljnjih update ako ideja pronadje zainteresovanost cs communitya.


**************************
Dokumentacija
**************************

U configs/ban-config.php morate odluciti da li zelite koristenje auth keya prilikom koristenja api servisa. Ako je iskljucen auth nije potrebno postaviti apikey u url

Note: trenutno ne radi ta funkcija

Example: domain/v1/api/banlist?apikey=nekiauthkey


1. API: pokazi sve banove iz ban liste = radi

Example get: domain/v1/api/banlist?apikey=nekiauthkey

Returns: 
{
  "success": true,
  "errors": [
    
  ],
  "data": [
    {
      "id": "1",
      "nick": "kalle",
      "steamid": "1",
      "ip": "1111212",
      "resource": "dwd212312",
      "server_ip": "1313`3`3`3`3`3`3`",
      "updated_at": "2019-09-18 20:02:09",
      "created_at": "2019-09-18 20:02:09"
    },
    {
      "id": "8",
      "nick": "kalle1g",
      "steamid": "kkk",
      "ip": "33333333",
      "resource": "",
      "server_ip": "gggg",
      "updated_at": "2019-10-04 21:23:28",
      "created_at": "2019-10-04 21:23:28"
    }
  ]
}

2. API: pokazi specifican ban = radi

type = steamid,nick, ip
id = vrijednost type preko kojeg zelite da vidite vrijednost bana ako postoji, ako ne postoji vraca se prazan input 

Example get: domain/v1/api/banview/{type}/{id}?apikey=nekiauthkey

Returns: 
{
  "success": true,
  "errors": [
    
  ],
  "data": [
    {
      "id": "1",
      "nick": "kalle",
      "steamid": "1",
      "ip": "1111212",
      "resource": "dwd212312",
      "server_ip": "1313`3`3`3`3`3`3`",
      "updated_at": "2019-09-18 20:02:09",
      "created_at": "2019-09-18 20:02:09"
    }
  ]
}

3. API: provjeri da li je igrac banovan = radi
Note: razlika izmedju checkplayer i banview je ta sto ne morate provjeravati u vasem kodu da li je igrac banovan, sa checkplayer dobijete gotovu informaciju u obliku bool

type = steamid,nick, ip
id = vrijednost type preko kojeg zelite da vidite vrijednost bana ako postoji, ako ne postoji vraca se prazan input 

Example get: domain/v1/api/checkplayer/{type}/{id}?apikey=nekiauthkey

Returns:
{
  "success": true,
  "errors": [
    
  ],
  "data": true
}

4. API: dodaj ban = radi
Note: banadd radi ali samo sa trusted informacijama i nema nikakve provjere kao ni sanatizacije inputa, te se mora zastitit u buducnosti
Ako izostavite neki parametar vrv ce baciti error te ban nece biti upisan regularno

Example get: domain/v1/api/banadd/?apikey=1&nick=kly&steamid=50&resource=opengl&server_ip=localhost&ip=mojip

Returns: //

5. API: izbrisi ban = radi

type = steamid,nick, ip
id = id bana kojeg zelite izbrisati

Example get: domain/v1/api/bandelete/{type}/{id}?apikey=nekiauthkey

Returns:
{
  "success": true,
  "errors": [
    
  ],
  "data": true
}

6. API: version = radi

id = api / plugin

Example get: domain/v1/api/version/{id}?apikey=nekiauthkey

Returns:
{
  "success": true,
  "errors": [
    
  ],
  "data": true
}


HVALA SVIMA KOJI OVO PROCITAJU, NEMA VECE BUDALE OD TEBE LPPP.












