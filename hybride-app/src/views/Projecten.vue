<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title slot="start">Projecten</ion-title>
        <ion-button slot="end" fill="clear" color="secondary" @click="openModal">
          Nieuw <ion-icon slot="end" :icon="addCircleOutline"></ion-icon>
        </ion-button>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Projecten</ion-title>
        </ion-toolbar>
      </ion-header>

      <ProjectCard v-for="project in projecten" :key="project.pr_id" :pr_naam="project.pr_naam" :pr_code="project.pr_code"
        :pr_omschrijving="project.pr_omschrijving" :pr_id="project.pr_id" @openProjectModal="openProjectModal" @projectenUpdated="refreshProjecten"/>
      <ProjectModal :isModalOpen="isModalOpen" :projectDetails="selectedProjectDetails" @closeModal="closeModal" @projectenUpdated="refreshProjecten" @projectAdded="openToast"/>
      <ion-toast :id="'open-toast'" :message="'Project succesvol toegevoegd!'" :duration="3000"></ion-toast>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButton, IonIcon, onIonViewWillEnter, IonToast } from '@ionic/vue';
import { addCircleOutline } from 'ionicons/icons';
import { ref, inject, defineEmits } from 'vue';
import ProjectCard from '@/components/ProjectCard.vue';
import ProjectModal from '@/components/ProjectModal.vue';

const axios = inject('axios');
const projecten = ref([]);
const isModalOpen = ref(false);

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
    });
}

const openToast = () => {
    const toastInstance = document.getElementById('open-toast');
    console.log(toastInstance);
    if (toastInstance) {
        toastInstance.present();
    }
};

const refreshProjecten = () => {
  getProjecten();
};

const emit = defineEmits();
onIonViewWillEnter(() => {
  getProjecten();
});

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const selectedProjectDetails = ref({});

const openProjectModal = (projectDetails) => {
  selectedProjectDetails.value = projectDetails;
  emit('updateValues', projectDetails);
  openModal();
};
</script>
