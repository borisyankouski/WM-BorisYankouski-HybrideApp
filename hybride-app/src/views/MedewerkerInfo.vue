<template>
    <ion-page>
        <ion-header :translucent="true">
            <ion-toolbar>
                <ion-row id="topToolbarRow">
                    <ion-col size="1.5">
                        <ion-button id="backButton" fill="clear" color="primary" href="tabs/tab1">
                            <ion-icon size="small" slot="icon-only" :icon="chevronBackOutline"></ion-icon>
                        </ion-button>
                    </ion-col>

                    <ion-col id="selectCol">
                        <ion-select v-model="selectedMedewerkerID" :interface-options="{ translucent: true, header: 'Kies medewerker' }" aria-label="Medewerker Code" interface="action-sheet"
                            label-placement="floating" @ionChange="selectionChanged">
                            <ion-select-option v-for="medewerker in medewerkers" :value="medewerker.mw_id">{{ medewerker.mw_voornaam
                            }} {{ medewerker.mw_familienaam }}</ion-select-option>
                            <!-- TODO als het kan specialisatie tonen in de opties lijst (eventueel ook projectcode in ProjectInfo.vue) -->
                        </ion-select>
                    </ion-col>
                </ion-row>
            </ion-toolbar>
            <ion-toolbar>
                <ion-searchbar id="medewerkerInfoSearch" @ionInput="handleSearch" placeholder=" Zoeken"></ion-searchbar>
            </ion-toolbar>
        </ion-header>
        <ion-content :fullscreen="true">
            <ion-header collapse="condense">
                <ion-toolbar>
                    <ion-title size="large">{{ getMedewerkerNaam() }}</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-list lines="full" mode="ios">
                <MedewerkerProjectItem v-for="project in projecten" :key="project.pr_id"
                    :pr_naam="project.pr_naam" :pr_code="project.pr_code" :mw_id="selectedMedewerkerID"
                    :pr_id="project.pr_id" :listIndex="project.listIndex" :isActive="project.isActive" />
            </ion-list>
        </ion-content>
    </ion-page>
</template>
  
<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonList, onIonViewWillEnter, IonSelect, IonSelectOption, IonSearchbar, IonButton, IonBackButton, IonIcon, IonRow, IonCol } from '@ionic/vue';
import { ref, inject } from 'vue';
import MedewerkerProjectItem from '@/components/MedewerkerProjectItem.vue';
import { chevronBackOutline } from 'ionicons/icons';

const allProjecten = ref([]);
const projecten = ref([]);

const axios = inject('axios');

const selectedMedewerkerID = ref(null);

const medewerkers = ref([]);
let activeProjectIDs = [];

const getMedewerkerNaam = () => {
    const selectedMedewerker = medewerkers.value.find(medewerker => medewerker.mw_id == selectedMedewerkerID.value);
    return selectedMedewerker ? `${selectedMedewerker.mw_voornaam} ${selectedMedewerker.mw_familienaam}` : '';
};

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
            console.log(response.data.data);
            for (let i = 0, end = response.data.data.length; i < end; i++) {
                medewerkers.value.push(response.data.data[i]);
            }
        });
};

const getActiveProjectIDs = async () => {
    try {
        const response = await axios.get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php', {
            params: {
                mw_id: selectedMedewerkerID.value
            }
        });

        console.log(response.data.data);

        if (response.status !== 200) {
            console.log('Error tijdens het ophalen van projecten' + response.status);
            return;
        }

        if (!response.data.data) {
            console.log('response.data.data is niet OK');
            return;
        }
        activeProjectIDs = response.data.data.map(item => item.pr_id);
    } catch (error) {
        console.error('Error tijdens het ophalen van projecten', error);
    }
};

const getProjecten = async () => {
    try {
        const response = await axios.get('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTEN.php');

        console.log(response.data.data);

        if (response.status !== 200) {
            console.log('Error tijdens het ophalen van projecten' + response.status);
            return;
        }

        if (!response.data.data) {
            console.log('response.data.data is niet OK');
            return;
        }

        projecten.value = response.data.data.map((item, index) => ({
            pr_id: item.pr_id,
            pr_naam: item.pr_naam,
            pr_code: item.pr_code,
            listIndex: index + 1,
            isActive: activeProjectIDs.includes(item.pr_id)
        }));

        allProjecten.value = projecten.value;
    } catch (error) {
        console.error('Error tijdens het ophalen van projecten', error);
    }
};

const handleSearch = (event) => {
    const searchTerm = event.target.value.toLowerCase();
    if (searchTerm == '') {
        projecten.value = allProjecten.value;
    } else {
        projecten.value = allProjecten.value.filter((project) => {
            const projectName = project.pr_naam.toLowerCase();
            const projectCode = project.pr_code.toLowerCase();
            return (projectName.includes(searchTerm) || projectCode.includes(searchTerm));
        });
    }
};

const clearSearchbar = () => {
  const medewerkerInfoSearch = document.querySelector('#medewerkerInfoSearch');
  medewerkerInfoSearch.value = '';
}

const refreshProjecten = async () => {
    activeProjectIDs = [];
    await getActiveProjectIDs();
    projecten.value = [];
    await getProjecten();
};

const selectionChanged = () => {
    refreshProjecten();
    clearSearchbar();
};

onIonViewWillEnter(() => {
    selectedMedewerkerID.value = parseInt(localStorage.getItem('selectedMedewerkerID'));
    getMedewerkers();
    refreshProjecten();
});
</script>

<style scoped>
#topToolbarRow {
    max-height: 50px;
    margin-top: -10px;
}

#selectCol.ios {
    padding-right: 12px;
}

#selectCol {
    padding-right: 7px;
}

#backButton.ios {
    margin-left: -10px;
    margin-top: 0px;
}

#backButton {
    margin-left: -10px;
    margin-top: 10px;
}

ion-searchbar.ios {
    padding-top: 3px;
}

ion-searchbar {
    padding-top: 0px;
}
</style>
  