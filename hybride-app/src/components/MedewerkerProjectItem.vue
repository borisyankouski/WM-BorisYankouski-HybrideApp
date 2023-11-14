<template>
    <ion-item>
        <ion-toggle :enable-on-off-labels="true" color="success" :data-pr_id="pr_id" :checked="isActiveProject"
            @ion-change="toggleChanged">
            <ion-label>
                <ion-row>
                    <strong>{{ pr_naam }}</strong>
                </ion-row>
                <ion-row>
                    <ion-note color="secondary">{{ pr_code }}</ion-note>

                </ion-row>
            </ion-label>
        </ion-toggle>
    </ion-item>
    <ion-toast :translucent="true" :id="'open-toast'" :message="'Medewerker succesvol toegevoegd!'" :duration="1000"></ion-toast>
</template>

<script setup>
import { defineProps, inject, ref } from 'vue';
import { IonItem, IonToggle, IonLabel, IonNote, IonRow, IonToast } from '@ionic/vue';
import { trashOutline, cloudUploadOutline, addCircleOutline, addCircle } from 'ionicons/icons';
const { pr_naam, pr_code, pr_id, mw_id, listIndex, isActive } = defineProps(['pr_naam', 'pr_code', 'pr_id', 'mw_id', 'listIndex', 'isActive']);
const isActiveProject = ref(isActive);

const toggleChanged = (event) => {
    if (event.detail.checked) {
        addProjectToMedewerker(pr_id, mw_id);
        console.log('Adding project ' + pr_id + ' to medewerker ' + mw_id);
        openToast('post');
    }
    else {
        removeProjectFromMedewerker(pr_id, mw_id);
        console.log('Removing project ' + pr_id + ' from medewerker ' + mw_id);
        openToast('delete');
    }
};

const openToast = (type) => {
    const toastInstance = document.getElementById('open-toast');
    if (type == 'post') {
        toastInstance.icon = addCircleOutline;
        toastInstance.cssClass = 'addToast';
        toastInstance.message = `${pr_code} toegevoegd aan medewerker!`;
    } else if (type == 'delete') {
        toastInstance.icon = trashOutline;
        toastInstance.cssClass = 'deleteToast';
        toastInstance.message = `${pr_code} verwijderd van medewerker!`;
    }
    if (toastInstance) {
        toastInstance.present();
    }
};

const axios = inject('axios');
const addProjectToMedewerker = (pr_id, mw_id) => {
    axios
        .post('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php', {
            mw_id: mw_id,
            pr_id: pr_id
        }
        )
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het posten van project' + response.status)
            }
            console.log('Project ' + pr_id + ' added to medewerker ' + mw_id);
        });
};

const removeProjectFromMedewerker = (pr_id, mw_id) => {
    axios
        .delete('https://www.kovskib.com/MW/RESTfulAPI/api/PROJECTMEDEWERKERS.php', {
            data: {
                mw_id: mw_id,
                pr_id: pr_id
            }
        })
        .then(response => {
            if (response.status !== 200) {
                console.log('Error tijdens het posten van project' + response.status)
            }
            console.log('Project ' + pr_id + ' added to medewerker ' + mw_id);
        });
};
</script>

<style scoped>
.addToast::part(icon) {
    color: #2fdf75;
}

.deleteToast::part(icon) {
    color: #ff4961;
}
</style>