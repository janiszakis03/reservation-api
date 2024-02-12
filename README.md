# Sporta salonu rezervāciju API risinājums
# Ievads

Šis API risinājums ir izstrādāts, lai ļautu lietotājiem veikt rezervācijas sporta salonos, izmantojot RESTful interfeisu. Katram sporta salonam ir savs darba laiks, un lietotāji var izvēlēties brīvu laika slotu, lai rezervētu.
# Pieejamie resursi

/api/reservations: Rezervāciju resurss, kur lietotāji var izveidot jaunas rezervācijas, atcelt esošās rezervācijas vai iegūt informāciju par esošajām rezervācijām.
# Rezervāciju izveide

Lietotāji var izveidot jaunas rezervācijas, nosūtot POST pieprasījumu uz /api/reservations. Jānorāda sporta centra ID, sākuma laiks un beigu laiks. Rezervācija tiks izveidota tikai tad, ja norādītais laika slots ir brīvs.
# Rezervāciju atcelšana

Esošās rezervācijas var atcelt, nosūtot DELETE pieprasījumu uz /api/reservations/{id}, kur {id} ir rezervācijas identifikators. Atceltā rezervācija tiks noņemta no sistēmas.
# Rezervāciju atjaunošana

## Apraksts

PUT /api/reservations/{id} ļauj lietotājiem atjaunot rezervācijas laika slotus konkrētā sporta centrā.

## Pieprasījuma Parametri

| Parametrs     | Tips    | Apraksts                                    |
|---------------|---------|---------------------------------------------|
| id            | Integer | Rezervācijas identifikators                 |
| start_time    | String  | Jaunais rezervācijas sākuma laiks (formāts: "HH:MM:SS") |
| end_time      | String  | Jaunais rezervācijas beigu laiks (formāts: "HH:MM:SS")   |

# Rezervāciju pārvaldība

Lietotāji var pārvaldīt savas rezervācijas, izmantojot pieejamos API resursus. Viņi var izveidot jaunas rezervācijas, atcelt esošās vai mainīt rezervēto laika slotu, ja tas ir pieejams.
# Kļūdu apstrāde

API risinājums atgriež atbilstošus kļūdu statusa kodus un ziņojumus, ja notiek kļūda. Piemēram, ja lietotājs mēģina rezervēt jau aizņemtu laika slotu, tiks atgriezta kļūdas atbilde ar statusa kodu 422 un atbilstošu ziņojumu.

Šī dokumentācija sniedz pamatinformāciju par pieejamajiem API resursiem un to darbību, lai lietotāji varētu izmantot API risinājumu efektīvi.
