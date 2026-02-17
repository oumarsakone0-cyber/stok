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

    <!-- Register Card -->
    <div :style="registerCardStyle" class="fade-in-up">
      <!-- Premium Badge -->
      <div :style="badgeStyle">
        <svg :style="sparkleStyle" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7.4-6.3-4.6-6.3 4.6 2.3-7.4-6-4.6h7.6z"/>
        </svg>
        <span>Premium</span>
      </div>

      <!-- Logo & Title -->
      <div :style="headerStyle">
        <div :style="logoContainerStyle">
          <svg :style="logoStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <div :style="appNameStyle">NEXT STOCK</div>
        <h1 :style="titleStyle">Créer un compte</h1>
        <p :style="subtitleStyle">Rejoignez la plateforme de gestion de stock nouvelle génération</p>
      </div>

      <!-- Alert Messages -->
      <Transition name="alert">
        <div v-if="errorMessage" :style="errorAlertStyle">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
          {{ errorMessage }}
        </div>
      </Transition>

      <Transition name="alert">
        <div v-if="successMessage" :style="successAlertStyle">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
          {{ successMessage }}
        </div>
      </Transition>

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

      <!-- Register Form -->
      <form @submit.prevent="handleRegister" :style="formStyle">
        <!-- Particulier Form Fields -->
        <template v-if="userType === 'particulier'">
          <!-- Nom -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Nom
            </label>
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

          <!-- Prénom -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Prénom
            </label>
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

          <!-- Contact -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              Contact
            </label>
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

          <!-- Email -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              Adresse email
            </label>
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

          <!-- Type d'activité -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
              Type d'activité
            </label>
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
        </template>

        <!-- Entreprise Form Fields -->
        <template v-if="userType === 'entreprise'">
          <!-- Nom Admin -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Nom de l'administrateur
            </label>
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

          <!-- Prénom Admin -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Prénom de l'administrateur
            </label>
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

          <!-- Nom de l'entreprise -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
              Nom de l'entreprise
            </label>
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

          <!-- Contact Entreprise -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              Contact entreprise
            </label>
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

          <!-- Email Entreprise -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              Email entreprise
            </label>
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

          <!-- Adresse Entreprise -->
          <div :style="formGroupStyle">
            <label :style="labelStyle">
              <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Adresse entreprise
            </label>
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
        </template>

        <!-- Password Input -->
        <div :style="formGroupStyle">
          <label :style="labelStyle">
            <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Mot de passe
          </label>
          <div :style="passwordWrapperStyle">
            <input 
              v-model="formData.password"
              :type="showPassword ? 'text' : 'password'"
              :style="getPasswordInputStyle"
              @focus="focusedInput = 'password'"
              @blur="focusedInput = null"
              @input="checkPasswordStrength"
              placeholder="••••••••"
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
          <label :style="labelStyle">
            <svg :style="labelIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Confirmer le mot de passe
          </label>
          <div :style="passwordWrapperStyle">
            <input 
              v-model="formData.passwordConfirm"
              :type="showPasswordConfirm ? 'text' : 'password'"
              :style="getPasswordConfirmInputStyle"
              @focus="focusedInput = 'passwordConfirm'"
              @blur="focusedInput = null"
              placeholder="••••••••"
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

        <!-- Submit Button -->
        <button 
          type="submit" 
          :style="submitButtonStyle"
          :disabled="loading || !isFormValid"
          @mouseenter="submitHovered = true"
          @mouseleave="submitHovered = false"
        >
          <span v-if="!loading">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="8.5" cy="7" r="4"></circle>
              <line x1="20" y1="8" x2="20" y2="14"></line>
              <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            S'inscrire
          </span>
          <span v-else :style="loadingSpinnerStyle">
            <div class="spinner"></div>
            Inscription en cours...
          </span>
        </button>
      </form>

      <!-- Footer -->
      <div :style="footerStyle">
        <p :style="footerTextStyle">
          Déjà un compte ? 
          <a href="#" :style="loginLinkStyle" @click.prevent="goToLogin">Se connecter</a>
        </p>
        <p :style="footerCopyrightStyle">
          © 2026 Gestion Commerciale · Sécurisé et fiable
        </p>
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
const userType = ref('particulier') // 'particulier' or 'entreprise'

const passwordRequirements = reactive({
  uppercase: false,
  lowercase: false,
  number: false,
  special: false
})

const passwordStrength = ref(0) // 0-100

const formData = reactive({
  // Commun
  email: '',
  password: '',
  passwordConfirm: '',
  
  // Particulier
  nom: '',
  prenom: '',
  contact: '',
  type_activite: '',
  
  // Entreprise
  nom_admin: '',
  prenom_admin: '',
  nom_entreprise: '',
  contact_entreprise: '',
  adresse_entreprise: ''
})

const checkPasswordStrength = () => {
  const password = formData.password
  
  // Reset requirements
  passwordRequirements.uppercase = /[A-Z]/.test(password)
  passwordRequirements.lowercase = /[a-z]/.test(password)
  passwordRequirements.number = /[0-9]/.test(password)
  passwordRequirements.special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
  
  // Calculate strength (0-100)
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

const isFormValid = computed(() => {
  if (!formData.password || !formData.passwordConfirm) return false
  if (formData.password !== formData.passwordConfirm) return false
  if (passwordStrength.value < 100) return false
  
  if (userType.value === 'particulier') {
    return formData.nom && formData.prenom && formData.contact && formData.email && formData.type_activite
  } else {
    return formData.nom_admin && formData.prenom_admin && formData.nom_entreprise && 
           formData.contact_entreprise && formData.email && formData.adresse_entreprise
  }
})

const handleRegister = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  
  // Validation
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
    // Prepare payload based on user type
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

// Styles
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

const registerCardStyle = {
  width: '100%',
  maxWidth: '620px',
  background: 'white',
  borderRadius: '32px',
  padding: '52px 44px',
  boxShadow: '0 24px 80px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05), 0 0 0 0 rgba(16, 185, 129, 0.1)',
  position: 'relative',
  zIndex: 1,
  border: '1px solid rgba(16, 185, 129, 0.08)'
}

const badgeStyle = {
  display: 'inline-flex',
  alignItems: 'center',
  gap: '8px',
  background: 'linear-gradient(135deg, #10b98115 0%, #05966910 100%)',
  border: '1px solid #10b98130',
  padding: '8px 18px',
  borderRadius: '50px',
  fontSize: '0.8rem',
  fontWeight: '700',
  color: '#059669',
  marginBottom: '28px',
  letterSpacing: '0.05em',
  textTransform: 'uppercase',
  boxShadow: '0 4px 12px rgba(16, 185, 129, 0.15)'
}

const sparkleStyle = {
  width: '16px',
  height: '16px',
  color: '#10b981',
  animation: 'sparkle 2s ease-in-out infinite'
}

const headerStyle = {
  textAlign: 'center',
  marginBottom: '32px'
}

const logoContainerStyle = {
  width: '80px',
  height: '80px',
  margin: '0 auto 20px',
  background: 'linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%)',
  borderRadius: '24px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  boxShadow: '0 16px 40px rgba(16, 185, 129, 0.35), 0 0 0 4px rgba(16, 185, 129, 0.1)',
  position: 'relative',
  '&::before': {
    content: '""',
    position: 'absolute',
    inset: '-2px',
    borderRadius: '24px',
    padding: '2px',
    background: 'linear-gradient(135deg, #10b981, #059669, #047857)',
    WebkitMask: 'linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0)',
    WebkitMaskComposite: 'xor',
    maskComposite: 'exclude',
    opacity: 0.3
  }
}

const logoStyle = {
  width: '42px',
  height: '42px',
  color: 'white',
  strokeWidth: '2.5',
  filter: 'drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1))'
}

const titleStyle = {
  fontSize: '28px',
  fontWeight: '700',
  color: '#0f172a',
  margin: '0 0 8px 0',
  letterSpacing: '-0.02em'
}

const appNameStyle = {
  fontSize: '18px',
  fontWeight: '800',
  background: 'linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%)',
  WebkitBackgroundClip: 'text',
  WebkitTextFillColor: 'transparent',
  backgroundClip: 'text',
  margin: '0 0 12px 0',
  letterSpacing: '0.1em',
  textTransform: 'uppercase'
}

const subtitleStyle = {
  fontSize: '15px',
  color: '#64748b',
  margin: '0',
  fontWeight: '500'
}

const errorAlertStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '12px',
  padding: '14px 16px',
  background: '#fef2f2',
  border: '1px solid #fecaca',
  borderRadius: '12px',
  color: '#991b1b',
  fontSize: '14px',
  fontWeight: '500',
  marginBottom: '20px'
}

const successAlertStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '12px',
  padding: '14px 16px',
  background: '#dcfce7',
  border: '1px solid #86efac',
  borderRadius: '12px',
  color: '#166534',
  fontSize: '14px',
  fontWeight: '500',
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

const labelStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  fontSize: '14px',
  fontWeight: '600',
  color: '#334155',
  letterSpacing: '0.01em'
}

const labelIconStyle = {
  width: '16px',
  height: '16px',
  strokeWidth: '2'
}

const getInputStyle = (inputName) => ({
  width: '100%',
  padding: '14px 16px',
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

const submitButtonStyle = computed(() => ({
  width: '100%',
  padding: '16px',
  background: submitHovered.value && isFormValid.value
    ? 'linear-gradient(135deg, #059669 0%, #047857 100%)'
    : isFormValid.value
    ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)'
    : 'linear-gradient(135deg, #94a3b8 0%, #64748b 100%)',
  color: 'white',
  border: 'none',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '600',
  cursor: loading.value || !isFormValid.value ? 'not-allowed' : 'pointer',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '10px',
  transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
  boxShadow: submitHovered.value && !loading.value && isFormValid.value
    ? '0 12px 28px rgba(16, 185, 129, 0.4)'
    : '0 4px 12px rgba(16, 185, 129, 0.25)',
  transform: submitHovered.value && !loading.value && isFormValid.value ? 'translateY(-2px)' : 'translateY(0)',
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
  borderTop: '1px solid #f1f5f9',
  textAlign: 'center'
}

const footerTextStyle = {
  fontSize: '14px',
  color: '#64748b',
  margin: '0 0 8px 0',
  fontWeight: '500'
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

const footerCopyrightStyle = {
  fontSize: '13px',
  color: '#94a3b8',
  margin: '0',
  fontWeight: '500'
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

* {
  font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
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

@keyframes sparkle {
  0%, 100% {
    opacity: 1;
    transform: scale(1) rotate(0deg);
  }
  50% {
    opacity: 0.7;
    transform: scale(1.2) rotate(180deg);
  }
}

.fade-in-up {
  animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) backwards;
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
</style>
