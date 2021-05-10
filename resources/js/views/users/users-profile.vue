<script>
import Layout from "../../layouts/main";
import PageHeader from "../../components/page-header";

import { revenueChart } from "./data-profile";

import axios from "axios";

/**
 * Contacts-Profile component
 */
export default {
  components: { Layout, PageHeader },
  data() {
    return {
      revenueChart: revenueChart,
      title: "Profil",
      items: [
        {
          text: "Utilisateur",
          href: "/"
        },
        {
          text: "Profil",
          active: true
        }
      ],
      user: null,
    mounted (){

        axios.get("api/users/profile/1")
            .then(response => {
                console.log(response.data);
                this.user = response.data;
            })
            .catch(error => console.log(error))
        },
    };
  }
};
</script>

<template>
  <Layout>
    <PageHeader :title="title" :items="items" />
    <div class="row">
      <div class="col-xl-12">
        <div class="card overflow-hidden">
          <div class="bg-soft bg-primary">
            <div class="row">
              <div class="col-7">
                <div class="text-primary p-3">
                  <h5 class="text-primary">Votre profil</h5>
                </div>
              </div>

            </div>
          </div>
          <div class="card-body pt-0">
            <div class="row">
              <div class="col-sm-4">
                <div class="avatar-md profile-user-wid mb-4">
                  <img
                    src="/images/users/avatar-1.png"
                    alt
                    class="img-thumbnail rounded-circle"
                  />
                </div>
              </div>

                <h4 class="card-title mb-4">Les informations personnelles</h4>

                <div class="table-responsive mb-0">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th scope="row">Nom :</th>
                            <td>Admin<!--{{ user.name }}--></td>
                        </tr>

                        <tr>
                            <th scope="row">Prénom :</th>
                            <td>Admin<!--{{ user.firstname }}--></td>
                        </tr>
                        <tr>
                            <th scope="row">Téléphone :</th>
                            <td>+212 661-000009<!--{{ user.phone}}--></td>
                        </tr>
                        <tr>
                            <th scope="row">E-mail :</th>
                            <td>admin@admin.com <!--{{ user.email }}--></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
        <!-- end card -->
        </div>


    </div>
    <!-- end row -->
  </Layout>
</template>
