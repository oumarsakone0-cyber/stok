<template>
  <div :style="containerStyle">
    <!-- Animated Background -->
    <div class="animated-bg">
      <div class="gradient-orb orb-1"></div>
      <div class="gradient-orb orb-2"></div>
      <div class="gradient-orb orb-3"></div>
      <div class="gradient-orb orb-4"></div> 
    </div>

    <!-- Subtle Grid Pattern -->
    <div :style="gridPatternStyle"></div>

    <!-- Main Container -->
    <div :style="mainContainerStyle">
      <!-- Left Side - Features -->
      <div :style="leftSideStyle" class="fade-in">
        <div :style="logoHeaderStyle">
          <div :style="logoContainerStyle">
            <svg :style="logoStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          <h1 :style="brandTitleStyle">Next Stock</h1>
        </div>

        <div :style="featuresContainerStyle">
          <div :style="featureItemStyle" v-for="(feature, index) in features" :key="index">
            <div :style="featureIconStyle">
              <component :is="'div'" v-html="feature.icon"></component>
            </div>
            <div>
              <h3 :style="featureTitleStyle">{{ feature.title }}</h3>
              <p :style="featureDescStyle">{{ feature.description }}</p>
            </div>
          </div>
        </div>

        <div :style="footerLinksStyle">
          <a href="#" :style="footerLinkStyle">Conditions</a>
          <span :style="separatorStyle">·</span>
          <a href="#" :style="footerLinkStyle">Confidentialité</a>
          <span :style="separatorStyle">·</span>
          <a href="#" :style="footerLinkStyle">Documentation</a>
        </div>
      </div>

      <!-- Right Side - Registration Form -->
      <div :style="rightSideStyle" class="fade-in-delay">
        <div :style="formCardStyle">
          <!-- Progress Indicator -->
          <div :style="progressIndicatorStyle">
            <div :style="progressStepStyle(1)">
              <div :style="progressStepCircleStyle(1)">
                <svg v-if="currentStep > 1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span v-else>1</span>
              </div>
              <span :style="progressStepLabelStyle(1)">Informations personnelles</span>
            </div>
            <div :style="progressLineStyle(1)"></div>
            <div :style="progressStepStyle(2)">
              <div :style="progressStepCircleStyle(2)">
                <span>2</span>
              </div>
              <span :style="progressStepLabelStyle(2)">Sécurité et finalisation</span>
            </div>
          </div>

          <!-- Header -->
          <div :style="formHeaderStyle">
            <h2 :style="formTitleStyle">{{ currentStep === 1 ? 'Inscription' : 'Sécurité et finalisation' }}</h2>
            <p :style="formSubtitleStyle">{{ currentStep === 1 ? 'Créez votre compte Next Stock' : 'Finalisez votre inscription' }}</p>
          </div>

          <!-- Alert Messages -->
          <Transition name="alert">
            <div v-if="errorMessage" :style="errorAlertStyle">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              {{ errorMessage }}
            </div>
          </Transition>

          <Transition name="alert">
            <div v-if="successMessage" :style="successAlertStyle">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
              </svg>
              {{ successMessage }}
            </div>
          </Transition>

          <!-- Register Form -->
          <form @submit.prevent="handleFormSubmit" :style="formStyle">
            <!-- Step 1: Personal Information -->
            <div v-if="currentStep === 1" :style="stepContainerStyle">
              <!-- User Type Selection -->
              <div :style="userTypeContainerStyle">
                <label :style="userTypeLabelStyle">Type de compte</label>
                <div :style="userTypeButtonsStyle">
                  <button
                    type="button"
                    :style="getUserTypeButtonStyle('particulier')"
                    @click="userType = 'particulier'"
                  >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Particulier
                  </button>
                  <button
                    type="button"
                    :style="getUserTypeButtonStyle('entreprise')"
                    @click="userType = 'entreprise'"
                  >
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Entreprise
                  </button>
                </div>
              </div>

              <!-- Particulier Form Fields -->
              <template v-if="userType === 'particulier'">
                <!-- Nom et Prénom sur une ligne -->
                <div :style="twoColumnsRowStyle">
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Nom</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <input 
                        v-model="formData.nom"
                        type="text"
                        :style="getInputStyle('nom')"
                        @focus="focusedInput = 'nom'"
                        @blur="focusedInput = null"
                        placeholder="Votre nom"
                        required
                      />
                    </div>
                  </div>
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Prénom</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <input 
                        v-model="formData.prenom"
                        type="text"
                        :style="getInputStyle('prenom')"
                        @focus="focusedInput = 'prenom'"
                        @blur="focusedInput = null"
                        placeholder="Votre prénom"
                        required
                      />
                    </div>
                  </div>
                </div>

                <!-- Contact et Email sur une ligne -->
                <div :style="twoColumnsRowStyle">
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Contact</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                      </svg>
                      <input 
                        v-model="formData.contact"
                        type="tel"
                        :style="getInputStyle('contact')"
                        @focus="focusedInput = 'contact'"
                        @blur="focusedInput = null"
                        placeholder="+225 XX XX XX XX"
                        required
                      />
                    </div>
                  </div>
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Email</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                      <input 
                        v-model="formData.email"
                        type="email"
                        :style="getInputStyle('email')"
                        @focus="focusedInput = 'email'"
                        @blur="focusedInput = null"
                        placeholder="exemple@sogetrag.com"
                        required
                        autocomplete="email"
                      />
                    </div>
                  </div>
                </div>

                <!-- Type d'activité -->
                <div :style="formGroupStyle">
                  <label :style="labelStyle">Type d'activité</label>
                  <div :style="inputWrapperStyle">
                    <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <select 
                      v-model="formData.type_activite"
                      :style="getInputStyle('type_activite')"
                      @focus="focusedInput = 'type_activite'"
                      @blur="focusedInput = null"
                      required
                    >
                      <option value="">Sélectionnez votre type d'activité...</option>
                      <option value="boutique">Boutique</option>
                      <option value="magasin">Magasin</option>
                      <option value="entrepot">Entrepôt</option>
                      <option value="supermarché">Supermarché</option>
                      <option value="pharmacie">Pharmacie</option>
                      <option value="restaurant">Restaurant</option>
                      <option value="e-commerce">E-commerce</option>
                      <option value="distributeur">Distributeur</option>
                      <option value="grossiste">Grossiste</option>
                      <option value="détaillant">Détaillant</option>
                      <option value="autre">Autre activité</option>
                    </select>
                  </div>
                </div>
              </template>

              <!-- Entreprise Form Fields -->
              <template v-if="userType === 'entreprise'">
                <!-- Nom et Prénom Admin sur une ligne -->
                <div :style="twoColumnsRowStyle">
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Nom admin</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <input 
                        v-model="formData.nom_admin"
                        type="text"
                        :style="getInputStyle('nom_admin')"
                        @focus="focusedInput = 'nom_admin'"
                        @blur="focusedInput = null"
                        placeholder="Nom de l'admin"
                        required
                      />
                    </div>
                  </div>
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Prénom admin</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      <input 
                        v-model="formData.prenom_admin"
                        type="text"
                        :style="getInputStyle('prenom_admin')"
                        @focus="focusedInput = 'prenom_admin'"
                        @blur="focusedInput = null"
                        placeholder="Prénom de l'admin"
                        required
                      />
                    </div>
                  </div>
                </div>

                <!-- Nom entreprise et Contact sur une ligne -->
                <div :style="twoColumnsRowStyle">
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Nom entreprise</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                      </svg>
                      <input 
                        v-model="formData.nom_entreprise"
                        type="text"
                        :style="getInputStyle('nom_entreprise')"
                        @focus="focusedInput = 'nom_entreprise'"
                        @blur="focusedInput = null"
                        placeholder="Nom de votre entreprise"
                        required
                      />
                    </div>
                  </div>
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Contact</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                      </svg>
                      <input 
                        v-model="formData.contact_entreprise"
                        type="tel"
                        :style="getInputStyle('contact_entreprise')"
                        @focus="focusedInput = 'contact_entreprise'"
                        @blur="focusedInput = null"
                        placeholder="+225 XX XX XX XX"
                        required
                      />
                    </div>
                  </div>
                </div>

                <!-- Email et Adresse sur une ligne -->
                <div :style="twoColumnsRowStyle">
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Email</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                      <input 
                        v-model="formData.email"
                        type="email"
                        :style="getInputStyle('email')"
                        @focus="focusedInput = 'email'"
                        @blur="focusedInput = null"
                        placeholder="contact@entreprise.com"
                        required
                        autocomplete="email"
                      />
                    </div>
                  </div>
                  <div :style="formGroupStyle">
                    <label :style="labelStyle">Adresse</label>
                    <div :style="inputWrapperStyle">
                      <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                      </svg>
                      <input 
                        v-model="formData.adresse_entreprise"
                        type="text"
                        :style="getInputStyle('adresse_entreprise')"
                        @focus="focusedInput = 'adresse_entreprise'"
                        @blur="focusedInput = null"
                        placeholder="Adresse complète"
                        required
                      />
                    </div>
                  </div>
                </div>
              </template>

              <!-- Navigation Button for Step 1 -->
              <div :style="navigationButtonsStyle">
                <button 
                  type="button"
                  :style="nextButtonStyle"
                  @click="goToNextStep"
                  :disabled="!isStep1Valid"
                >
                  Suivant
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Step 2: Security and Finalization -->
            <div v-if="currentStep === 2" :style="stepContainerStyle">
              <!-- Password Input -->
              <div :style="formGroupStyle">
                <label :style="labelStyle">Mot de passe *</label>
                <div :style="passwordWrapperStyle">
                  <input 
                    v-model="formData.password"
                    :type="showPassword ? 'text' : 'password'"
                    :style="getPasswordInputStyle"
                    @focus="focusedInput = 'password'"
                    @blur="focusedInput = null"
                    @input="checkPasswordStrength"
                    placeholder="Votre mot de passe"
                    required
                    autocomplete="new-password"
                  />
                  <button 
                    type="button" 
                    :style="togglePasswordStyle"
                    @click="showPassword = !showPassword"
                    tabindex="-1"
                  >
                    <svg v-if="!showPassword" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                      <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                      <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                  </button>
                </div>
                
                <!-- Password Strength Indicator -->
                <div v-if="formData.password" :style="passwordStrengthContainerStyle">
                  <div :style="passwordStrengthBarStyle">
                    <div :style="getPasswordStrengthBarFillStyle"></div>
                  </div>
                  <div :style="passwordStrengthTextStyle">
                    Sécurité: {{ passwordStrengthText }}
                  </div>
                  <div :style="passwordRequirementsStyle">
                    <div :style="getRequirementStyle('uppercase')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline v-if="passwordRequirements.uppercase" points="20 6 9 17 4 12"/>
                        <circle v-else cx="12" cy="12" r="10"/>
                      </svg>
                      Lettre majuscule
                    </div>
                    <div :style="getRequirementStyle('lowercase')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline v-if="passwordRequirements.lowercase" points="20 6 9 17 4 12"/>
                        <circle v-else cx="12" cy="12" r="10"/>
                      </svg>
                      Lettre minuscule
                    </div>
                    <div :style="getRequirementStyle('number')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline v-if="passwordRequirements.number" points="20 6 9 17 4 12"/>
                        <circle v-else cx="12" cy="12" r="10"/>
                      </svg>
                      Chiffre
                    </div>
                    <div :style="getRequirementStyle('special')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline v-if="passwordRequirements.special" points="20 6 9 17 4 12"/>
                        <circle v-else cx="12" cy="12" r="10"/>
                      </svg>
                      Caractère spécial
                    </div>
                  </div>
                </div>
              </div>

              <!-- Confirm Password Input -->
              <div :style="formGroupStyle">
                <label :style="labelStyle">Confirmer le mot de passe *</label>
                <div :style="passwordWrapperStyle">
                  <input 
                    v-model="formData.passwordConfirm"
                    :type="showPasswordConfirm ? 'text' : 'password'"
                    :style="getPasswordConfirmInputStyle"
                    @focus="focusedInput = 'passwordConfirm'"
                    @blur="focusedInput = null"
                    placeholder="Confirmez votre mot de passe"
                    required
                    autocomplete="new-password"
                  />
                  <button 
                    type="button" 
                    :style="togglePasswordStyle"
                    @click="showPasswordConfirm = !showPasswordConfirm"
                    tabindex="-1"
                  >
                    <svg v-if="!showPasswordConfirm" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                      <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                      <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                  </button>
                </div>
                <div v-if="formData.passwordConfirm && formData.password !== formData.passwordConfirm" :style="passwordMismatchStyle">
                  Les mots de passe ne correspondent pas
                </div>
              </div>

              <!-- Checkboxes -->
              <div :style="checkboxesContainerStyle">
                <label :style="checkboxLabelStyle">
                  <input 
                    type="checkbox" 
                    v-model="acceptTerms"
                    :style="checkboxStyle"
                    required
                  />
                  <span>J'accepte les <a href="#" :style="linkStyle">Conditions d'utilisation</a> et la <a href="#" :style="linkStyle">Politique de confidentialité</a></span>
                </label>
                <label :style="checkboxLabelStyle">
                  <input 
                    type="checkbox" 
                    v-model="acceptMarketing"
                    :style="checkboxStyle"
                  />
                  <span>J'accepte de recevoir des emails marketing et des notifications (optionnel)</span>
                </label>
              </div>

              <!-- Navigation Buttons for Step 2 -->
              <div :style="navigationButtonsStyle">
                <button 
                  type="button"
                  :style="previousButtonStyle"
                  @click="goToPreviousStep"
                >
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                  </svg>
                  Précédent
                </button>
                <button 
                  type="submit" 
                  :style="submitButtonStyle"
                  :disabled="loading || !isStep2Valid"
                  @mouseenter="submitHovered = true"
                  @mouseleave="submitHovered = false"
                >
                  <span v-if="!loading">
                    Créer
                  </span>
                  <span v-else :style="loadingSpinnerStyle">
                    <div class="spinner"></div>
                    Inscription en cours...
                  </span>
                </button>
              </div>
            </div>
          </form>

          <!-- Footer -->
          <div :style="footerStyle">
            <p :style="footerTextStyle">
              Déjà un compte ? 
              <a href="#" :style="loginLinkStyle" @click.prevent="goToLogin">Se connecter</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const API_BASE_URL = 'https://sogetrag.com/apistok'

const loading = ref(false)
const submitHovered = ref(false)
const focusedInput = ref(null)
const showPassword = ref(false)
const showPasswordConfirm = ref(false)
const errorMessage = ref('')
const successMessage = ref('')
const userType = ref('particulier')
const currentStep = ref(1)
const acceptTerms = ref(false)
const acceptMarketing = ref(false)

const passwordRequirements = reactive({
  uppercase: false,
  lowercase: false,
  number: false,
  special: false
})

const passwordStrength = ref(0)

const formData = reactive({
  email: '',
  password: '',
  passwordConfirm: '',
  nom: '',
  prenom: '',
  contact: '',
  type_activite: '',
  nom_admin: '',
  prenom_admin: '',
  nom_entreprise: '',
  contact_entreprise: '',
  adresse_entreprise: ''
})

const features = [
  {
    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v4H3z"></path><path d="M3 7h18v14H3z"></path></svg>',
    title: 'Suivi des stocks',
    description: 'Surveillez vos entrées et sorties en temps réel pour éviter les ruptures.'
  },
  {
    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22"></path><path d="M5 5h14v14H5z"></path></svg>',
    title: 'Alertes automatiques',
    description: 'Recevez des notifications pour les produits faibles en stock ou périmés.'
  },
  {
    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"></path><path d="M9 9h6v6H9z"></path></svg>',
    title: 'Rapports détaillés',
    description: 'Analysez vos ventes, achats et tendances pour une gestion efficace.'
  },
  {
    icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>',
    title: 'Sécurité et permissions',
    description: 'Contrôlez qui peut accéder, modifier ou valider les stocks dans l\'application.'
  }
]

const checkPasswordStrength = () => {
  const password = formData.password
  passwordRequirements.uppercase = /[A-Z]/.test(password)
  passwordRequirements.lowercase = /[a-z]/.test(password)
  passwordRequirements.number = /[0-9]/.test(password)
  passwordRequirements.special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
  
  const requirementsMet = [
    passwordRequirements.uppercase,
    passwordRequirements.lowercase,
    passwordRequirements.number,
    passwordRequirements.special
  ].filter(Boolean).length
  
  passwordStrength.value = (requirementsMet / 4) * 100
}

const passwordStrengthText = computed(() => {
  if (passwordStrength.value === 0) return 'Très faible'
  if (passwordStrength.value === 25) return 'Faible'
  if (passwordStrength.value === 50) return 'Moyen'
  if (passwordStrength.value === 75) return 'Fort'
  if (passwordStrength.value === 100) return 'Très fort'
  return 'Faible'
})

const passwordStrengthColor = computed(() => {
  if (passwordStrength.value === 0) return '#ef4444'
  if (passwordStrength.value === 25) return '#f59e0b'
  if (passwordStrength.value === 50) return '#eab308'
  if (passwordStrength.value === 75) return '#84cc16'
  if (passwordStrength.value === 100) return '#10b981'
  return '#ef4444'
})

const isStep1Valid = computed(() => {
  if (userType.value === 'particulier') {
    return formData.nom && formData.prenom && formData.contact && formData.email && formData.type_activite
  } else {
    return formData.nom_admin && formData.prenom_admin && formData.nom_entreprise && 
           formData.contact_entreprise && formData.email && formData.adresse_entreprise
  }
})

const isStep2Valid = computed(() => {
  if (!formData.password || !formData.passwordConfirm) return false
  if (formData.password !== formData.passwordConfirm) return false
  if (passwordStrength.value < 100) return false
  if (!acceptTerms.value) return false
  return true
})

const goToNextStep = () => {
  if (isStep1Valid.value) {
    currentStep.value = 2
    errorMessage.value = ''
  } else {
    errorMessage.value = 'Veuillez remplir tous les champs requis'
  }
}

const goToPreviousStep = () => {
  currentStep.value = 1
  errorMessage.value = ''
}

const handleFormSubmit = async () => {
  if (currentStep.value === 1) {
    goToNextStep()
    return
  }
  await handleRegister()
}

const handleRegister = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  
  if (formData.password !== formData.passwordConfirm) {
    errorMessage.value = 'Les mots de passe ne correspondent pas'
    return
  }
  
  if (passwordStrength.value < 100) {
    errorMessage.value = 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial'
    return
  }
  
  loading.value = true

  try {
    const payload = {
      user_type: userType.value,
      email: formData.email,
      password: formData.password,
      contact: userType.value === 'particulier' ? formData.contact : formData.contact_entreprise
    }
    
    if (userType.value === 'particulier') {
      payload.nom = formData.nom
      payload.prenom = formData.prenom
      payload.type_activite = formData.type_activite
    } else {
      payload.nom_admin = formData.nom_admin
      payload.prenom_admin = formData.prenom_admin
      payload.nom_entreprise = formData.nom_entreprise
      payload.adresse_entreprise = formData.adresse_entreprise
      payload.entreprise = formData.nom_entreprise
    }

    const response = await fetch(`${API_BASE_URL}/api_auth.php?action=register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })

    const data = await response.json()

    if (data.success) {
      successMessage.value = 'Inscription réussie ! Redirection vers la connexion...'
      setTimeout(() => {
        router.push('/login')
      }, 2000)
    } else {
      errorMessage.value = data.error || 'Erreur lors de l\'inscription'
    }
  } catch (error) {
    console.error('Erreur d\'inscription:', error)
    errorMessage.value = 'Impossible de se connecter au serveur'
  } finally {
    loading.value = false
  }
}

const goToLogin = () => {
  router.push('/login')
}

// Styles - Exactement comme Login.vue
const containerStyle = {
  minHeight: '100vh',
  background: 'linear-gradient(135deg, #fdfcfb 0%, #f7f4f0 25%, #faf8f6 50%, #f5f2ee 75%, #fdfcfb 100%)',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  padding: '40px 24px',
  fontFamily: '"SF Pro Display", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
  position: 'relative',
  overflow: 'hidden'
}

const gridPatternStyle = {
  position: 'fixed',
  top: 0,
  left: 0,
  width: '100%',
  height: '100%',
  backgroundImage: `
    linear-gradient(rgba(0, 0, 0, 0.015) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 0, 0, 0.015) 1px, transparent 1px)
  `,
  backgroundSize: '60px 60px',
  zIndex: 0,
  pointerEvents: 'none'
}

const mainContainerStyle = {
  width: '100%',
  maxWidth: '1200px',
  display: 'grid',
  gridTemplateColumns: '1fr 1fr',
  gap: '60px',
  alignItems: 'center',
  position: 'relative',
  zIndex: 1
}

const leftSideStyle = {
  color: '#36454F',
  padding: '40px'
}

const logoHeaderStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '16px',
  marginBottom: '60px'
}

const logoContainerStyle = {
  width: '48px',
  height: '48px',
  background: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
  borderRadius: '12px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  boxShadow: '0 8px 20px rgba(16, 185, 129, 0.3)'
}

const logoStyle = {
  width: '28px',
  height: '28px',
  color: 'white',
  strokeWidth: '2.5'
}

const brandTitleStyle = {
  fontSize: '24px',
  fontWeight: '700',
  color: '#36454F',
  margin: 0,
  letterSpacing: '-0.02em'
}

const featuresContainerStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '32px'
}

const featureItemStyle = {
  display: 'flex',
  gap: '20px',
  alignItems: 'flex-start'
}

const featureIconStyle = {
  width: '48px',
  height: '48px',
  background: 'rgba(16, 185, 129, 0.1)',
  borderRadius: '12px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  flexShrink: 0,
  color: '#10b981',
  border: '1px solid rgba(16, 185, 129, 0.2)'
}

const featureTitleStyle = {
  fontSize: '16px',
  fontWeight: '600',
  color: '#36454F',
  margin: '0 0 8px 0'
}

const featureDescStyle = {
  fontSize: '14px',
  color: '#3A3A3A',
  margin: 0,
  lineHeight: '1.6'
}

const footerLinksStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '12px',
  marginTop: '60px'
}

const footerLinkStyle = {
  fontSize: '14px',
  color: 'gray',
  textDecoration: 'none',
  transition: 'color 0.2s',
  '&:hover': {
    color: 'rgba(255, 255, 255, 0.8)'
  }
}

const separatorStyle = {
  color: 'rgba(255, 255, 255, 0.3)'
}

const rightSideStyle = {
  display: 'flex',
  justifyContent: 'center'
}

const formCardStyle = {
  width: '100%',
  maxWidth: '420px',
  boxShadow: `
    0 2px 6px rgba(0,0,0,0.08),
    0 12px 24px rgba(0,0,0,0.12),
    0 22px 40px rgba(0,0,0,0.10),
    0 38px 32px -12px rgba(255,255,255,0.18)
  `,
  background: 'rgba(255,255,255,0.9)',
  backdropFilter: 'blur(20px)',
  borderRadius: '24px',
  padding: '36px',
  border: '1px solid rgba(255,255,255,0.25)'
}

const progressIndicatorStyle = {
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'space-between',
  marginBottom: '32px',
  position: 'relative'
}

const progressStepStyle = (step) => ({
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  gap: '8px',
  flex: 1,
  position: 'relative',
  zIndex: 2
})

const progressStepCircleStyle = (step) => ({
  width: '40px',
  height: '40px',
  borderRadius: '50%',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  fontSize: '16px',
  fontWeight: '700',
  color: currentStep.value >= step ? 'white' : '#94a3b8',
  background: currentStep.value >= step 
    ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)'
    : '#e2e8f0',
  border: currentStep.value > step ? '2px solid #10b981' : 'none',
  boxShadow: currentStep.value >= step ? '0 4px 12px rgba(16, 185, 129, 0.3)' : 'none',
  transition: 'all 0.3s ease'
})

const progressStepLabelStyle = (step) => ({
  fontSize: '12px',
  fontWeight: '600',
  color: currentStep.value >= step ? '#10b981' : '#94a3b8',
  textAlign: 'center',
  whiteSpace: 'nowrap'
})

const progressLineStyle = (step) => ({
  flex: 1,
  height: '2px',
  background: currentStep.value > step ? 'linear-gradient(90deg, #10b981 0%, #059669 100%)' : '#e2e8f0',
  margin: '0 12px',
  transition: 'all 0.3s ease'
})

const stepContainerStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '20px'
}

const formHeaderStyle = {
  marginBottom: '32px'
}

const formTitleStyle = {
  fontSize: '28px',
  fontWeight: '700',
  color: '#36454F',
  margin: '0 0 8px 0',
  letterSpacing: '-0.02em'
}

const formSubtitleStyle = {
  fontSize: '14px',
  color: '#3A3A3A',
  margin: 0
}

const errorAlertStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '12px',
  padding: '12px 16px',
  background: 'rgba(239, 68, 68, 0.1)',
  border: '1px solid rgba(239, 68, 68, 0.3)',
  borderRadius: '12px',
  color: '#ef4444',
  fontSize: '14px',
  marginBottom: '20px'
}

const successAlertStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '12px',
  padding: '12px 16px',
  background: 'rgba(16, 185, 129, 0.1)',
  border: '1px solid rgba(16, 185, 129, 0.3)',
  borderRadius: '12px',
  color: '#10b981',
  fontSize: '14px',
  marginBottom: '20px'
}

const userTypeContainerStyle = {
  marginBottom: '24px'
}

const userTypeLabelStyle = {
  display: 'block',
  fontSize: '14px',
  fontWeight: '600',
  color: '#334155',
  marginBottom: '12px',
  letterSpacing: '0.01em'
}

const userTypeButtonsStyle = {
  display: 'flex',
  gap: '12px'
}

const getUserTypeButtonStyle = (type) => ({
  flex: 1,
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '8px',
  padding: '14px 16px',
  border: userType.value === type ? '2px solid #10b981' : '2px solid #e2e8f0',
  borderRadius: '12px',
  background: userType.value === type ? '#f0fdf4' : 'white',
  color: userType.value === type ? '#10b981' : '#64748b',
  fontSize: '14px',
  fontWeight: '600',
  cursor: 'pointer',
  transition: 'all 0.2s',
  '&:hover': {
    borderColor: userType.value === type ? '#10b981' : '#cbd5e1',
    background: userType.value === type ? '#f0fdf4' : '#f8fafc'
  }
})

const formStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '20px'
}

const formGroupStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '8px'
}

const twoColumnsRowStyle = {
  display: 'flex',
  gap: '16px'
}

const labelStyle = {
  fontSize: '14px',
  fontWeight: '500',
  color: 'black'
}

const inputWrapperStyle = {
  position: 'relative',
  display: 'flex',
  alignItems: 'center'
}

const inputIconStyle = {
  position: 'absolute',
  left: '14px',
  width: '18px',
  height: '18px',
  color: '#94a3b8',
  zIndex: 1,
  pointerEvents: 'none'
}

const getInputStyle = (inputName) => ({
  width: '100%',
  padding: '14px 16px 14px 44px',
  border: focusedInput.value === inputName ? '2px solid #10b981' : '2px solid #e2e8f0',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '500',
  color: '#1e293b',
  transition: 'all 0.2s',
  outline: 'none',
  boxSizing: 'border-box',
  background: focusedInput.value === inputName ? '#f8fafc' : 'white',
  cursor: inputName === 'type_activite' ? 'pointer' : 'text',
  appearance: inputName === 'type_activite' ? 'none' : 'auto',
  backgroundImage: inputName === 'type_activite' ? 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 12 12\'%3E%3Cpath fill=\'%2310b981\' d=\'M6 9L1 4h10z\'/%3E%3C/svg%3E")' : 'none',
  backgroundRepeat: inputName === 'type_activite' ? 'no-repeat' : 'repeat',
  backgroundPosition: inputName === 'type_activite' ? 'right 16px center' : '0 0',
  paddingRight: inputName === 'type_activite' ? '44px' : '16px'
})

const passwordWrapperStyle = {
  position: 'relative',
  display: 'flex',
  alignItems: 'center'
}

const getPasswordInputStyle = computed(() => ({
  width: '100%',
  padding: '14px 48px 14px 16px',
  border: focusedInput.value === 'password' ? '2px solid #10b981' : '2px solid #e2e8f0',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '500',
  color: '#1e293b',
  transition: 'all 0.2s',
  outline: 'none',
  boxSizing: 'border-box',
  background: focusedInput.value === 'password' ? '#f8fafc' : 'white'
}))

const getPasswordConfirmInputStyle = computed(() => ({
  width: '100%',
  padding: '14px 48px 14px 16px',
  border: focusedInput.value === 'passwordConfirm' 
    ? (formData.password === formData.passwordConfirm ? '2px solid #10b981' : '2px solid #ef4444')
    : '2px solid #e2e8f0',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '500',
  color: '#1e293b',
  transition: 'all 0.2s',
  outline: 'none',
  boxSizing: 'border-box',
  background: focusedInput.value === 'passwordConfirm' ? '#f8fafc' : 'white'
}))

const togglePasswordStyle = {
  position: 'absolute',
  right: '12px',
  background: 'none',
  border: 'none',
  cursor: 'pointer',
  color: '#94a3b8',
  padding: '8px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  borderRadius: '8px',
  transition: 'all 0.2s',
  '&:hover': {
    background: '#f1f5f9',
    color: '#64748b'
  }
}

const passwordStrengthContainerStyle = {
  marginTop: '8px'
}

const passwordStrengthBarStyle = {
  width: '100%',
  height: '6px',
  background: '#e2e8f0',
  borderRadius: '3px',
  overflow: 'hidden',
  marginBottom: '8px'
}

const getPasswordStrengthBarFillStyle = computed(() => ({
  width: `${passwordStrength.value}%`,
  height: '100%',
  background: passwordStrengthColor.value,
  borderRadius: '3px',
  transition: 'all 0.3s ease'
}))

const passwordStrengthTextStyle = computed(() => ({
  fontSize: '12px',
  fontWeight: '600',
  color: passwordStrengthColor.value,
  marginBottom: '12px'
}))

const passwordRequirementsStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '8px'
}

const getRequirementStyle = (requirement) => ({
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  fontSize: '12px',
  color: passwordRequirements[requirement] ? '#10b981' : '#94a3b8',
  fontWeight: passwordRequirements[requirement] ? '600' : '500'
})

const passwordMismatchStyle = {
  fontSize: '12px',
  color: '#ef4444',
  fontWeight: '500',
  marginTop: '4px'
}

const navigationButtonsStyle = {
  display: 'flex',
  gap: '12px',
  marginTop: '8px'
}

const nextButtonStyle = {
  width: '100%',
  padding: '16px',
  background: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
  color: 'white',
  border: 'none',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '600',
  cursor: 'pointer',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '8px',
  transition: 'all 0.3s ease',
  boxShadow: '0 4px 12px rgba(16, 185, 129, 0.25)',
  '&:hover:not(:disabled)': {
    transform: 'translateY(-2px)',
    boxShadow: '0 8px 20px rgba(16, 185, 129, 0.35)'
  },
  '&:disabled': {
    opacity: 0.5,
    cursor: 'not-allowed'
  }
}

const previousButtonStyle = {
  padding: '16px 24px',
  background: 'white',
  color: '#64748b',
  border: '2px solid #e2e8f0',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '600',
  cursor: 'pointer',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '8px',
  transition: 'all 0.2s ease',
  '&:hover': {
    borderColor: '#cbd5e1',
    background: '#f8fafc'
  }
}

const checkboxesContainerStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '16px',
  marginTop: '8px'
}

const checkboxLabelStyle = {
  display: 'flex',
  alignItems: 'flex-start',
  gap: '12px',
  fontSize: '14px',
  color: '#64748b',
  fontWeight: '500',
  cursor: 'pointer',
  lineHeight: '1.5'
}

const checkboxStyle = {
  width: '18px',
  height: '18px',
  minWidth: '18px',
  marginTop: '2px',
  cursor: 'pointer',
  accentColor: '#10b981'
}

const linkStyle = {
  color: '#10b981',
  fontWeight: '600',
  textDecoration: 'none',
  transition: 'color 0.2s',
  '&:hover': {
    color: '#059669',
    textDecoration: 'underline'
  }
}

const submitButtonStyle = computed(() => ({
  flex: 1,
  padding: '16px',
  background: submitHovered.value && isStep2Valid.value
    ? 'linear-gradient(135deg, #059669 0%, #047857 100%)'
    : isStep2Valid.value
    ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)'
    : 'linear-gradient(135deg, #94a3b8 0%, #64748b 100%)',
  color: 'white',
  border: 'none',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '600',
  cursor: loading.value || !isStep2Valid.value ? 'not-allowed' : 'pointer',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '10px',
  transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
  boxShadow: submitHovered.value && !loading.value && isStep2Valid.value
    ? '0 12px 28px rgba(16, 185, 129, 0.4)'
    : '0 4px 12px rgba(16, 185, 129, 0.25)',
  transform: submitHovered.value && !loading.value && isStep2Valid.value ? 'translateY(-2px)' : 'translateY(0)',
  letterSpacing: '0.01em',
  opacity: loading.value ? 0.7 : 1
}))

const loadingSpinnerStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '10px'
}

const footerStyle = {
  marginTop: '32px',
  paddingTop: '24px',
  borderTop: '1px solid rgba(255, 255, 255, 0.1)',
  textAlign: 'center'
}

const footerTextStyle = {
  fontSize: '14px',
  color: '#3A3A3A',
  margin: 0
}

const loginLinkStyle = {
  color: '#10b981',
  fontWeight: '600',
  textDecoration: 'none',
  transition: 'color 0.2s',
  '&:hover': {
    color: '#059669'
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

* {
  font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeInDelay {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes float {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(30px, -30px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
}

.fade-in {
  animation: fadeIn 0.6s ease-out;
}

.fade-in-delay {
  animation: fadeInDelay 0.6s ease-out 0.2s backwards;
}

.animated-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: 0;
  pointer-events: none;
}

.gradient-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.4;
  animation: float 25s ease-in-out infinite;
}

.orb-1 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(16, 185, 129, 0.3), transparent);
  top: -200px;
  left: -200px;
  animation-delay: 0s;
}

.orb-2 {
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(59, 130, 246, 0.25), transparent);
  top: 20%;
  right: -250px;
  animation-delay: 8s;
}

.orb-3 {
  width: 450px;
  height: 450px;
  background: radial-gradient(circle, rgba(245, 158, 11, 0.25), transparent);
  bottom: -150px;
  left: 25%;
  animation-delay: 16s;
}

.orb-4 {
  width: 550px;
  height: 550px;
  background: radial-gradient(circle, rgba(139, 92, 246, 0.2), transparent);
  top: 40%;
  left: 50%;
  animation-delay: 12s;
}

.spinner {
  width: 18px;
  height: 18px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top: 3px solid white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.alert-enter-active, .alert-leave-active {
  transition: all 0.3s ease;
}

.alert-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.alert-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Style pour le select */
select {
  cursor: pointer;
}

select option {
  padding: 12px;
  background: white;
  color: #1e293b;
}

select option:checked {
  background: #f0fdf4;
  color: #059669;
  font-weight: 600;
}

select:focus {
  border-color: #10b981 !important;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

@media (max-width: 968px) {
  div[style*="mainContainerStyle"] {
    grid-template-columns: 1fr !important;
  }
  
  div[style*="leftSideStyle"] {
    display: none !important;
  }
}
</style>
