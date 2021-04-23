<script>
/**
 * Login component
 */
export default {
    data() {
        return {
            email: "",
            password: "",
            tryingToLogIn: false,
            isAuthError: false,
        };
    },
    props: {
        submitUrl: {
            type: String,
            required: true,
        },
        authError: {
            type: String,
            required: false,
            default: () => null,
        },
    },
    mounted() {
        this.isAuthError = !!this.authError;
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
                                <h5 class="text-primary">Connexion</h5>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="/images/cmcp.jpg" alt class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div>
                        
                    </div>

                    <b-alert
                        v-model="isAuthError"
                        variant="danger"
                        class="mt-3"
                        dismissible
                    >{{ authError }}</b-alert
                    >

                    <b-form class="p-2" :action="submitUrl" method="POST">
                        <slot />
                        <b-form-group
                            id="input-group-1"
                            label="Email"
                            label-for="input-1"
                            class="mb-3"
                        >
                            <b-form-input
                                id="input-1"
                                name="email"
                                v-model="email"
                                type="text"
                                placeholder="Adresse e-mail"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group
                            id="input-group-2"
                            label="Mot de passe"
                            label-for="input-2"
                            class="mb-3"
                        >
                            <b-form-input
                                id="input-2"
                                v-model="password"
                                name="password"
                                type="password"
                                placeholder="Mot de passe"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-checkbox
                            class="form-check"
                            id="customControlInline"
                            name="checkbox-1"
                            value="accepted"
                            unchecked-value="not_accepted"
                        >
                            Se rappeller de moi
                        </b-form-checkbox>
                        <div class="mt-3 d-grid">
                            <b-button type="submit" variant="primary" class="btn-block"
                            >Se connecter</b-button
                            >
                        </div>

                        <div class="mt-4 text-center">
                            <a href="/reset/password" class="text-muted">
                                <i class="mdi mdi-lock mr-1"></i> Mot de passe oublié!
                            </a>
                        </div>
                    </b-form>
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card -->

            <div class="mt-5 text-center">

                <p>
                    © {{ new Date().getFullYear() }} Créé par
                    <i class="text-danger"></i> Agri 4.0
                </p>
            </div>
            <!-- end row -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</template>

