<template>
    <ion-card :data-pr_id="pr_id">
        <ion-card-header>
            <ion-card-title>{{ pr_naam }}</ion-card-title>
            <ion-card-subtitle>{{ pr_code }}</ion-card-subtitle>
        </ion-card-header>

        <ion-card-content>{{ pr_omschrijving }}</ion-card-content>

        <ion-row>
            <ion-button fill="clear" color="tertiary" aria-label="Favorite">
                Beheer <ion-icon slot="end" :icon="cogOutline" color="tertiary"></ion-icon>
            </ion-button>
            <ion-button fill="clear" color="danger" aria-label="Favorite">
                Verwijder <ion-icon slot="end" :icon="trashOutline" color="danger"></ion-icon>
            </ion-button>
        </ion-row>
    </ion-card>
</template>
  
<script setup>
import { IonCard, IonCardContent, IonCardHeader, IonCardSubtitle, IonCardTitle, IonButton, IonIcon, IonRow, IonCol } from '@ionic/vue';
import { cogOutline, trashOutline } from 'ionicons/icons';

defineProps({
    pr_naam: String,
    pr_code: String,
    pr_omschrijving: String,
    pr_id: Number
});
</script>
  
<style scoped>
#btnDelete button {
    align-self: flex-end;
}
</style>