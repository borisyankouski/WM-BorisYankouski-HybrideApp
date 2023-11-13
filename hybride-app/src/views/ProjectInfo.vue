<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title slot="start">EC7892</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content :fullscreen="true">
            <ion-header collapse="condense">
                <ion-toolbar>
                    <ion-title size="large">EC7892</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-list lines="full">
                <ProjectMedewerkerItem v-for="medewerker in medewerkers" :key="medewerker.mw_id"
                    :mw_naam="medewerker.mw_naam" :sp_naam="medewerker.sp_naam" :mw_id="medewerker.mw_id" :pr_id="projectID"
                    :listIndex="medewerker.listIndex" :isActive="medewerker.isActive"/>
            </ion-list>
        </ion-content>
    </ion-page>
</template>
  
<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonList, onIonViewWillEnter } from '@ionic/vue';
import { ref, inject } from 'vue';
import ProjectMedewerkerItem from '@/components/ProjectMedewerkerItem.vue';

const projectID = 1;
const medewerkers = ref([]);

const axios = inject('axios');

const activeMedewerkerIDs = [];

const getActiveMedewerkerIDs = () => {
    axios
        .get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php',{
            params: {
            pr_id: projectID
        }})
        .then(response => {
            console.log(response.data.data);
            if (response.status !== 200) {
                console.log('Error tijdens het ophalen van medewerkers' + response.status)
            }
            if (!response.data.data) {
                console.log('response.data.data is niet OK');
                return;
            }
            console.log(response.data);
            for (let i = 0, end = response.data.data.length; i < end; i++) {
                activeMedewerkerIDs.push(response.data.data[i].mw_id);
            }
        });
}

const getMedewerkers = () => {
    axios
        .get('https://www.kovskib.com/MW/RESTfulAPI/api/MEDEWERKERS.php')
        .then(response => {
            console.log(response.data.data);
            if (response.status !== 200) {
                console.log('Error tijdens het ophalen van medewerkers' + response.status)
            }
            if (!response.data.data) {
                console.log('response.data.data is niet OK');
                return;
            }
            console.log(response.data);
            for (let i = 0, end = response.data.data.length; i < end; i++) {
                const medewerker = {
                    mw_id: response.data.data[i].mw_id,
                    mw_naam: response.data.data[i].mw_voornaam + ' ' + response.data.data[i].mw_familienaam,
                    sp_naam: response.data.data[i].sp_naam,
                    listIndex: i + 1,
                    isActive: activeMedewerkerIDs.includes(response.data.data[i].mw_id)
                }
                medewerkers.value.push(medewerker);
            }
        });
}

onIonViewWillEnter(() => {
    getActiveMedewerkerIDs();
    getMedewerkers();
});
</script>
  