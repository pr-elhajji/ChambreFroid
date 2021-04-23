<script>
/**
 * Register component
 */
export default {
  data() {
    return {
        name: "",
        email: "",
        phone:"",
        password: "",
        password_confirmation: "",
        isRegisterError: false,
        registerSuccess: false,
    };
  },
  props: {
    submitUrl: {
      type: String,
      required: true,
    },
    regError: {
      type: String,
      required: false,
      default: () => null,
    },
  },
  mounted() {
    this.isRegisterError = !!this.regError;
  },
};
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
      <div class="card overflow-hidden">
        <div class="bg-soft bg-primary">
          <div class="row">
            <div class="col-7">
              <div class="text-primary p-4">
                <h5 class="text-primary">Créer un nouveau compte</h5>

              </div>
            </div>
            <div class="col-5 align-self-end">
              <img src="/images/profile-img.png" alt class="img-fluid" />
            </div>
          </div>
        </div>
        <div class="card-body pt-0">
          <div>
            <a href="/">
              <div class="avatar-md profile-user-wid mb-4">
                <span class="avatar-title rounded-circle bg-light">
                  <img src="/images/logo.svg" alt class="rounded-circle" height="34" />
                </span>
              </div>
            </a>
          </div>

          <b-alert
            v-model="registerSuccess"
            class="mt-3"
            variant="success"
            dismissible
          >Inscription réussi.</b-alert>

          <b-alert v-model="isRegisterError" class="mt-3" variant="danger" dismissible>{{regError}}</b-alert>

          <b-form class="p-2" :action="submitUrl" method="POST">
            <slot />
            <b-form-group id="email-group" label="Nom" label-for="name" class="mb-3">
              <b-form-input
                id="name"
                v-model="name"
                name="name"
                type="text"
                placeholder="Nom d'utilisateur"
              ></b-form-input>
            </b-form-group>

            <b-form-group id="fullname-group" label="Email" label-for="email" class="mb-3">
              <b-form-input
                id="email"
                name="email"
                v-model="email"
                type="email"
                placeholder="E-mail"
              ></b-form-input>
            </b-form-group>

              <b-form-group id="fullname-group" label="Téléphone" label-for="telephone" class="mb-3">
                  <b-form-input
                      id="telephone"
                      name="telephone"
                      v-model="phone"
                      type="telephone"
                      placeholder="Téléphone"
                  ></b-form-input>
              </b-form-group>

            <b-form-group id="password-group" label="Mot de passe" label-for="password" class="mb-3">
              <b-form-input
                id="password"
                v-model="password"
                name="password"
                type="password"
                placeholder="Saisir un mot de passe"
              ></b-form-input>
            </b-form-group>
            <b-form-group label="Confirmation de mot de passe" label-for="password-confirm" class="mb-3">
              <b-form-input
                id="password-confirm"
                v-model="password_confirmation"
                name="password_confirmation"
                type="password"
                placeholder="Confirmer le mot de passe"
              ></b-form-input>
            </b-form-group>

            <div class="mt-4 d-grid">
              <b-button type="submit" variant="primary" class="btn-block">Enregistrer</b-button>
            </div>
            <div class="mt-4 text-center">
              <h5 class="font-size-14 mb-3">Se connecter ...</h5>

              <ul class="list-inline">
                <li class="list-inline-item">
                  <a
                    href="javascript: void(0);"
                    class="social-list-item bg-primary text-white border-primary"
                  >
                    <i class="mdi mdi-facebook"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a
                    href="javascript: void(0);"
                    class="social-list-item bg-info text-white border-info"
                  >
                    <i class="mdi mdi-twitter"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a
                    href="javascript: void(0);"
                    class="social-list-item bg-danger text-white border-danger"
                  >
                    <i class="mdi mdi-google"></i>
                  </a>
                </li>
              </ul>
            </div>
          </b-form>
        </div>
        <!-- end card-body -->
      </div>
      <!-- end card -->

      <div class="mt-5 text-center">
        <p>
            Vous avez déjà un compte ?
          <a href="/login" class="fw-medium text-primary">Se connecter</a>
        </p>
          <p>
              © {{ new Date().getFullYear() }} Créée
              <i class="text-danger"></i> par Agri 4.0
          </p>
      </div>
    </div>
    <!-- end col -->
  </div>
</template>

<style lang="scss" module></style>
