# BlogIt

## Avtorja
Tilen Kelc, Domen Košir

## O BlogIt-IS
[BlogIt](https://tilenkelc.eu/Forum) je novo spletno omrežje, katero omogoča prijavo in registracijo uporabnikov. Stran omogoča tudi prikaz vseh objav, komentarjev in všečkov neprijavljenim uporabnikom, vendar pa le teh ne morejo spreminjati. Ko se uporabnik prijavi, dobi dostop do dodajanja in urejanja objav, prav tako jih lahko komentira in všečka. Če so mu objave določene osebe všeč, pa lahko tudi tej osebi začne slediti. Vsak uporabnik ima pregled nad vsemi svojimi objavami.

Stran ima tudi različne vrste privilegijev. Priviligirani uporabniki, kot so moderatorji in administratorji lahko imajo pregled nad vsemi uporabniki, ter objavami.

Administrator lahko uporabnika tudi blokira, nakar se mu onemogoči vse funkcije razen prijave.

## Namestitev
* Kloniranje repositorija
* Prenos in namestitev Xampp orodja
* Kreiranje podatkovne base z imenom forum v phpmyadmin
* Izvajanje migracij: `php artisan migrate:refresh`
* Poganjanje projekta: `php artisan serve`

![Landing Page](https://github.com/TilenKelc/Forum/blob/main/images/Screenshot_2023-01-09_102537.png)
![Add post](https://github.com/TilenKelc/Forum/blob/main/images/Screenshot_2023-01-09_102854.png)
![Admin page](https://github.com/TilenKelc/Forum/blob/main/images/image.png)

## Podatkovna baza
Podatkovna baza je zgrajena iz 6 tabel. 
![Database Scheme](https://github.com/TilenKelc/Forum/blob/main/images/pb.jpg)

### Avtentikacija in avtorizacija
Laravel že privzeto uporablja avtentikator. Za avtentikacijo API-ja, pa smo uporabili Sanctum.
![login](https://github.com/TilenKelc/Forum/blob/main/images/Screenshot_2023-01-09_102617.png)

## REST API
API je dokumentiran [tukaj](https://tilenkelc.eu/Forum/api/documentation). Omogoča izvajanje vseh funkcionalnosti, katere ima uporabnik.

## Mobilni odjemalec
![mobile](https://github.com/TilenKelc/Forum/blob/main/images/Screenshot_2023-01-09_113025.png)
