<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title slot="start">Medewerkers</ion-title>
        <ion-button id="addMedewerkerBtn" slot="end" fill="clear" color="secondary">
          Nieuw <ion-icon slot="end" :icon="addCircleOutline"></ion-icon>
        </ion-button>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Medewerkers</ion-title>
        </ion-toolbar>
      </ion-header>


      <MedewerkerCard v-for="medewerker in medewerkers" :key="medewerker.mw_id" :Voornaam="medewerker.mw_voornaam"
        :Familienaam="medewerker.mw_familienaam" :Specialisatie="medewerker.sp_naam" :sp_id="medewerker.sp_id"
        :mw_id="medewerker.mw_id" />
    </ion-content>
  </ion-page>
</template>

<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButton, IonIcon, onIonViewWillEnter } from '@ionic/vue';
import { addCircleOutline } from 'ionicons/icons';
import { ref, inject } from 'vue';
import MedewerkerCard from '@/components/MedewerkerCard.vue';

const medewerkers = ref([]);

const axios = inject('axios');

const getMedewerkers = () => {
  axios
    .get('https://www.kovskib.com/MW/RESTfulAPI/api/MEDEWERKERS.php')
    .then(response => {
      if (response.status !== 200) {
        console.log('Error tijdens het ophalen van medewerkers' + response.status)
      }
      if (!response.data.data) {
        console.log('response.data.data is niet OK');
        return;
      }
      console.log(response.data);
      medewerkers.value = [];
      for (let i = 0, end = response.data.data.length; i < end; i++) {
        medewerkers.value.push(response.data.data[i]);
      }
    });
}

onIonViewWillEnter(() => {
  getMedewerkers();
});
</script>
