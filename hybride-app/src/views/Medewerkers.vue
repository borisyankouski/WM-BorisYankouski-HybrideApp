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

      <ion-modal ref="modal" trigger="addMedewerkerBtn" @willDismiss="onWillDismiss">
        <ion-header>
          <ion-toolbar>
            <ion-buttons slot="start">
              <ion-button @click="cancel()">Cancel</ion-button>
            </ion-buttons>
            <ion-title>Welcome</ion-title>
            <ion-buttons slot="end">
              <ion-button :strong="true" @click="confirm()">Confirm</ion-button>
            </ion-buttons>
          </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
          <ion-item>
            <ion-input label="Enter your name" label-placement="stacked" ref="input" type="text"
              placeholder="Your name"></ion-input>
          </ion-item>
        </ion-content>
      </ion-modal>

    </ion-content>
  </ion-page>
</template>

<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButton, IonIcon, IonButtons, onIonViewWillEnter, IonModal, IonItem, IonInput } from '@ionic/vue';
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

// const addProject = () => {
//   console.log('Nieuw Project Click!')
// }

// modal
const message = ref('This modal example uses triggers to automatically open a modal when the button is clicked.');

const modal = ref();
const input = ref();

const cancel = () => modal.value.$el.dismiss(null, 'cancel');

const confirm = () => {
  const name = input.value.$el.value;
  modal.value.$el.dismiss(name, 'confirm');
};

const onWillDismiss = (ev) => {
  if (ev.detail.role === 'confirm') {
    message.value = `Hello, ${ev.detail.data}!`;
  }
};
</script>
