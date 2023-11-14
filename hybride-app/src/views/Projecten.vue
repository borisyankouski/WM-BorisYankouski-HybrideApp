<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar id="topToolbar">
        <ion-title slot="start">Projecten</ion-title>
        <ion-buttons :collapse="true" slot="end">
          <ion-button fill="clear" color="success" @click="openModal">
            Nieuw <ion-icon slot="end" :icon="addCircleOutline"></ion-icon>
          </ion-button>
        </ion-buttons>
      </ion-toolbar>

      <ion-toolbar>
        <ion-searchbar @ionInput="handleSearch" :enterkeyhint="search" placeholder=" Zoeken"></ion-searchbar>
      </ion-toolbar>

    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Projecten</ion-title>
          <ion-buttons :collapse="true" id="collapseNieuwButton" slot="end">
            <ion-button fill="clear" color="success" @click="openModal">
              Nieuw <ion-icon slot="end" :icon="addCircleOutline"></ion-icon>
            </ion-button>
          </ion-buttons>
        </ion-toolbar>
      </ion-header>

      <ProjectCard v-for="project in projecten" :key="project.pr_id" :pr_naam="project.pr_naam" :pr_code="project.pr_code"
        :pr_omschrijving="project.pr_omschrijving" :pr_id="project.pr_id" @projectenUpdated="refreshProjecten" />
      <ProjectModal :isModalOpen="isModalOpen" :projectDetails="null" :title="'Project Toevoegen'" :type="'post'"
        @closeModal="closeModal" @projectenUpdated="refreshProjecten" @projectAdded="openToast" />
      <ion-toast :translucent="true" :id="'open-toast'" :message="'Project succesvol toegevoegd!'" :duration="2000" :icon="addCircleOutline" cssClass="addToast"></ion-toast>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButton, IonButtons, IonIcon, onIonViewWillEnter, IonToast, IonSearchbar } from '@ionic/vue';
import { addCircleOutline } from 'ionicons/icons';
import { ref, inject, defineEmits } from 'vue';
import ProjectCard from '@/components/ProjectCard.vue';
import ProjectModal from '@/components/ProjectModal.vue';

const axios = inject('axios');
const allProjecten = ref([]);
const projecten = ref([]);
const isModalOpen = ref(false);
const emit = defineEmits();

const getProjecten = () => {
  axios
    .get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php')
    .then(response => {
      if (response.status !== 200) {
        console.log('Error tijdens het ophalen van projecten' + response.status)
      }
      if (!response.data.data) {
        console.log('response.data.data is niet OK');
        return;
      }
      console.log(response.data);
      projecten.value = response.data.data;
      allProjecten.value = projecten.value;
    });
}

const openToast = () => {
  const toastInstance = document.getElementById('open-toast');
  console.log(toastInstance);
  if (toastInstance) {
    toastInstance.present();
  }
};

const handleSearch = (event) => {
  const searchTerm = event.target.value.toLowerCase();
  if (searchTerm == '') {
    projecten.value = allProjecten.value;
  } else {
    projecten.value = allProjecten.value.filter((project) => {
      const projectNaam = project.pr_naam.toLowerCase();
      const projectCode = project.pr_code.toLowerCase();
      return (projectNaam.includes(searchTerm) || projectCode.includes(searchTerm));
    });
  }
};

const refreshProjecten = () => {
  getProjecten();
};

onIonViewWillEnter(() => {
  getProjecten();
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

.addToast::part(icon) {
    color: #2fdf75;
}

ion-searchbar {
    padding-top: 3px;
}
</style>