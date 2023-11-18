<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <!-- <ion-title>About me</ion-title> -->
        <ion-title>Boris Yankouski</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true" :scroll-y="false">
      <ion-header collapse="condense">
        <ion-toolbar>
          <!-- <ion-title size="large">About me</ion-title> -->
          <ion-title size="large">Boris Yankouski</ion-title>
        </ion-toolbar>
      </ion-header>

      <ion-card mode="ios" id="creatorCard">
        <img alt="Silhouette of mountains" src="../images/creator.jpg" />
        <ion-card-header>
          <!-- <ion-card-title>Boris Yankouski</ion-card-title>
          <ion-card-subtitle><a href="https://www.kovskib.com/">
              <div class="siteName">Kovsk</div>
              <div class="siteParenthesis">{</div>
              <div class="siteI">i</div>
              <div class="siteParenthesis">}</div>
              <div class="siteName">B</div>
            </a></ion-card-subtitle> -->


          <ion-card-title><a href="https://www.kovskib.com/">
              <div class="siteName">Kovsk</div>
              <div class="siteParenthesis">{</div>
              <div class="siteI">i</div>
              <div class="siteParenthesis">}</div>
              <div class="siteName">B</div>
            </a>
          </ion-card-title>
        </ion-card-header>

        <ion-card-content>
          De eigenaar en maker van deze hybride web applicatie. Voor meer van mijn projecten te bekijken kunt u naar mijn
          website <strong>KovskiB</strong> gaan.
        </ion-card-content>
      </ion-card>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonIcon } from '@ionic/vue';
import { exitOutline } from 'ionicons/icons'
</script>

<style scoped>
a {
  font-family: 'VT323', monospace;
  font-size: 40px;
  display: flex;
  flex-direction: row;
  /* color: #3dc2ff; */
  /* text-decoration: none; */
  /* font-style: italic; */
  /* color: #f0f0f0; */
}

/* .siteName { */
  /* color: #f0f0f0; */
  /* text-shadow: white 0px 0px 15px, white 0px 0px 45px; */
  /* text-shadow: white 0px 0px 5px, rgba(255, 255, 255, 0.5) 0px 0px 30px; */
/* } */

.siteParenthesis {
  color: #C586C0;
  /* text-shadow: #C586C0 0px 0px 5px, rgba(255, 255, 255, 0.5) 0px 0px 15px; */
}

.siteI {
  color: #71b5ed;
  /* text-shadow: #71b5ed 0px 0px 55px, rgba(255, 255, 255, 0.5) 0px 0px 15px; */
}
</style>