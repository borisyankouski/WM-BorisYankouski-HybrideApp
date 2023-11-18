# Functionaliteiten Hybride app

## Medewerkers.vue

* Searchbar om te filtreren op medewerker naam of specialiteit naam
* Nieuw button om medewerkers toe te voegen via **MedewerkerModal**
* **MedewerkerCards** om medewerkers te displayen

### MedewerkerCard.vue
* Naam, Specialiteit zichtbaar
* Edit knop om medewerkers te bewerken via **MedewerkerModal**
* Beheer knop om medewerkers hun projecten te zien en aan of uit te doen via **MedewerkerInfo**
* Verwijder knop om medewerkers te verwijderen na een confirmatie alert

### MedewerkerModal.vue
* Cancel knop om modal te sluiten
* Formchecking om te zien of verplichte velden ingevuld zijn
* Text input voor voornaam en familienaam
* Select input voor specialisatie
* Modal kan gebruikt worden voor toevoegen EN updaten van medewerkers
* Feedback toast bij het toevoegen/aanpassen van medewerker
* Feedback toast bij gefaalde formchecking

### MedewerkerInfo.vue
* Terug knop om terug naar Medewerkers te gaan
* Select om te wisselen tussen medewerkers
* Searchbar om projecten te filtreren op project naam of project code
* Lijst van **MedewerkerProjectItems**

### MedewerkerProjectItem.vue
* Project list item met toggles om project toe te voegen of te verwijderen van medewerker
* Feedback toast bij het toevoegen/verwijderen

## Projecten.vue

* Searchbar om te filtreren op project naam of project code
* Nieuw button om project toe te voegen via **ProjectModal**
* **ProjectCards** om projecten te displayen

### ProjectCard.vue
* Naam, Code, Omschrijving zichtbaar
* Edit knop om project te bewerken via **ProjectModal**
* Beheer knop om projecten hun medewerkers te zien en aan of uit te doen via **ProjectInfo**
* Verwijder knop om projecten te verwijderen na een confirmatie alert

### ProjectModal.vue
* Cancel knop om modal te sluiten
* Formchecking om te zien of verplichte velden ingevuld zijn
* Formchecking om te zien of project code in formaat AB1234 is
* Text input voor naam code en omschrijving
* Modal kan gebruikt worden voor toevoegen EN updaten van projecten
* Feedback toast bij het toevoegen/aanpassen van project
* Feedback toast bij gefaalde formchecking

### ProjectInfo.vue
* Terug knop om terug naar Projecten te gaan
* Select om te wisselen tussen projecten
* Searchbar om medewerkers te filtreren op medewerker naam of medewerker specialisatie
* Lijst van **ProjectMedewerkerItems**

### ProjectMedewerkerItem.vue
* Medewerker list item met toggles om medewerker toe te voegen of te verwijderen van project
* Feedback toast bij het toevoegen/verwijderen

## About.vue
* Card met informatie over mij
* Link naar main website (https://www.kovskib.com)