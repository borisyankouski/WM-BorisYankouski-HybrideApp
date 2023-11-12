<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title slot="start">Projecten</ion-title>
        <ion-button slot="end" fill="clear" color="secondary">
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
        :pr_omschrijving="project.pr_omschrijving" :pr_id="project.pr_id" />
    </ion-content>
  </ion-page>
</template>

<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButton, IonIcon, onIonViewWillEnter } from '@ionic/vue';
import { addCircleOutline } from 'ionicons/icons';
import { ref, inject } from 'vue';
import ProjectCard from '@/components/ProjectCard.vue';

const projecten = ref([]);

const axios = inject('axios');

const getProjecten = () => {
  axios
    .get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php')
    .then(response => {
      if (response.status !== 200) {
        console.log('Error tijdens het ophalen van medewerkers' + response.status)
      }
      if (!response.data.data) {
        console.log('response.data.data is niet OK');
        return;
      }
      console.log(response.data);
      projecten.value = [];
      for (let i = 0, end = response.data.data.length; i < end; i++) {
        projecten.value.push(response.data.data[i]);
      }
    });
}

onIonViewWillEnter(() => {
  getProjecten();
});
</script>