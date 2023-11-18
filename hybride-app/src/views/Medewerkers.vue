<template>
  <ion-page>
    <ion-header mode="ios" :translucent="true">
      <ion-toolbar id="topToolbar">
        <ion-title slot="start">Medewerkers</ion-title>
        <ion-buttons id="nieuwButton" :collapse="true" slot="end">
          <ion-button fill="clear" color="success" @click="openModal">
            Nieuw <ion-icon slot="end" :icon="addCircleOutline"></ion-icon>
          </ion-button>
        </ion-buttons>
      </ion-toolbar>

      <ion-toolbar>
        <ion-searchbar @ionInput="handleSearch" placeholder=" Zoeken"></ion-searchbar>
      </ion-toolbar>

    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Medewerkers</ion-title>
          <ion-buttons :collapse="true" id="collapseNieuwButton" slot="end">
            <ion-button fill="clear" color="success" @click="openModal">
              Nieuw <ion-icon slot="end" :icon="addCircleOutline"></ion-icon>
            </ion-button>
          </ion-buttons>
        </ion-toolbar>
      </ion-header>

      <MedewerkerCard mode="ios" v-for="medewerker in medewerkers" :key="medewerker.mw_id" :mw_voornaam="medewerker.mw_voornaam"
        :mw_familienaam="medewerker.mw_familienaam" :sp_naam="medewerker.sp_naam" :sp_id="medewerker.sp_id"
        :mw_id="medewerker.mw_id" @medewerkersUpdated="refreshMedewerkers" />
      <MedewerkerModal :isModalOpen="isModalOpen" :medewerkerDetails="null" :title="'Medewerker Toevoegen'" :type="'post'"
        @closeModal="closeModal" @medewerkersUpdated="refreshMedewerkers" @medewerkerAdded="openToast" />
      <ion-toast :translucent="true" :id="'medewerker-add-toast'" :message="'Medewerker succesvol toegevoegd!'" :duration="2000"
        :icon="addCircleOutline" cssClass="addToast"></ion-toast>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButton, IonButtons, IonIcon, onIonViewWillEnter, IonToast, IonSearchbar } from '@ionic/vue';
import { addCircleOutline } from 'ionicons/icons';
import { ref, inject, defineEmits } from 'vue';
import MedewerkerCard from '@/components/MedewerkerCard.vue';
import MedewerkerModal from '@/components/MedewerkerModal.vue';

const axios = inject('axios');
const allMedewerkers = ref([]);
const medewerkers = ref([]);
const isModalOpen = ref(false);
const emit = defineEmits();

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
      medewerkers.value = response.data.data;
      allMedewerkers.value = medewerkers.value;
    });
}

const openToast = () => {
  const toastInstance = document.getElementById('medewerker-add-toast');
  console.log(toastInstance);
  if (toastInstance) {
    toastInstance.present();
  }
};

const handleSearch = (event) => {
  const searchTerm = event.target.value.toLowerCase();
  if (searchTerm == '') {
    medewerkers.value = allMedewerkers.value;
  } else {
    medewerkers.value = allMedewerkers.value.filter((medewerker) => {
      const medewerkerNaam = medewerker.mw_voornaam.toLowerCase() + ' ' + medewerker.mw_familienaam.toLowerCase();
      const medewerkerSpecialisatie = medewerker.sp_naam.toLowerCase();
      return (medewerkerNaam.includes(searchTerm) || medewerkerSpecialisatie.includes(searchTerm));
    });
  }
};

const refreshMedewerkers = () => {
  getMedewerkers();
};

onIonViewWillEnter(() => {
  getMedewerkers();
});

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};
</script>

<style scoped>
#collapseNieuwButton {
  margin-top: 6px;
  margin-right: 3px;
}

#nieuwButton.ios {
  padding-right: 5px;
}

#nieuwButton {
  padding-right: 4px;
}

.addToast::part(icon) {
    color: #2fdf75;
}

ion-searchbar {
    padding-top: 3px;
}
</style>