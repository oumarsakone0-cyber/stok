<template>
  <div :style="containerStyle">
    <!-- Animated Background -->
    <div class="animated-bg">
      <div class="gradient-orb orb-1"></div>
      <div class="gradient-orb orb-2"></div>
      <div class="gradient-orb orb-3"></div>
    </div>

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

      <!-- Right Side - Login Form -->
      <div :style="rightSideStyle" class="fade-in-delay">
        <div :style="formCardStyle">
          <!-- Header -->
          <div :style="formHeaderStyle">
            <h2 :style="formTitleStyle">Connexion</h2>
            <p :style="formSubtitleStyle">Accédez à votre espace Next Stock</p>
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

          <!-- Social Login Buttons -->
          <div :style="socialButtonsStyle">
            <button :style="socialButtonStyle" @click="handleSocialLogin('google')">
              <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Google
            </button>
            <button :style="socialButtonStyle" @click="handleSocialLogin('github')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
              Github
            </button>
            <button :style="socialButtonStyle" @click="handleSocialLogin('gitlab')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path fill="#FC6D26" d="M22.65 14.39L12 22.13 1.35 14.39a.84.84 0 01-.3-.94l1.22-3.78 2.44-7.51A.42.42 0 014.82 2a.43.43 0 01.58 0 .42.42 0 01.11.18l2.44 7.49h8.1l2.44-7.51A.42.42 0 0118.6 2a.43.43 0 01.58 0 .42.42 0 01.11.18l2.44 7.51L23 13.45a.84.84 0 01-.35.94z"/>
              </svg>
              Gitlab
            </button>
          </div>

          <div :style="dividerStyle">
            <span>Ou</span>
          </div>

          <!-- Login Form -->
          <form @submit.prevent="handleLogin" :style="formStyle">
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
                  placeholder="alaskayoung@gmail.com"
                  required
                  autocomplete="email"
                />
              </div>
            </div>

            <div :style="formGroupStyle">
              <label :style="labelStyle">Mot de passe</label>
              <div :style="inputWrapperStyle">
                <svg :style="inputIconStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input 
                  v-model="formData.password"
                  :type="showPassword ? 'text' : 'password'"
                  :style="getPasswordInputStyle"
                  @focus="focusedInput = 'password'"
                  @blur="focusedInput = null"
                  placeholder="••••••••"
                  required
                  autocomplete="current-password"
                />
                <button 
                  type="button" 
                  :style="togglePasswordStyle"
                  @click="showPassword = !showPassword"
                  tabindex="-1"
                >
                  <svg v-if="!showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                  </svg>
                </button>
              </div>
              <p :style="passwordHintStyle">Minimum 8 caractères</p>
            </div>

            <button 
              type="submit" 
              :style="submitButtonStyle"
              :disabled="loading"
              @mouseenter="submitHovered = true"
              @mouseleave="submitHovered = false"
            >
              <span v-if="!loading">Se connecter</span>
              <span v-else :style="loadingSpinnerStyle">
                <div class="spinner"></div>
                Connexion...
              </span>
            </button>
          </form>

          <!-- Footer -->
          <div :style="formFooterStyle">
            <p :style="footerTextStyle">
              Vous n'avez pas de compte ? 
              <a href="#" :style="linkStyle" @click.prevent="goToRegister">S'inscrire</a>
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
import { useAuthStore } from '../../stores/authStore'
import { authLogin } from '../../services/api.js'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(false)
const submitHovered = ref(false)
const focusedInput = ref(null)
const showPassword = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

const formData = reactive({
  email: '',
  password: ''
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
    description: "Contrôlez qui peut accéder, modifier ou valider les stocks dans l'application."
  }
]

const handleLogin = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  loading.value = true

  try {
    const response = await authLogin(formData.email, formData.password)
    const data = response.data

    if (data.success) {
      await authStore.login(data.data)
      successMessage.value = 'Connexion réussie ! Redirection...'
      
      setTimeout(() => {
        const redirect = router.currentRoute.value.query.redirect
        router.push(redirect || '/')
      }, 1500)
    } else {
      errorMessage.value = data.error || 'Erreur lors de la connexion'
    }
  } catch (error) {
    console.error('Erreur de connexion:', error)
    const msg = error.response?.data?.error || error.message || 'Impossible de se connecter au serveur'
    errorMessage.value = msg
  } finally {
    loading.value = false
  }
}

const handleSocialLogin = (provider) => {
  alert(`Connexion ${provider} en cours de développement`)
}

const goToRegister = () => {
  router.push('/register')
}

// Styles
const containerStyle = {
  minHeight: '100vh',
  background: '#FAF9F6',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  padding: '20px',
  fontFamily: '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
  position: 'relative',
  overflow: 'hidden'
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
  width: '24px',
  height: '24px',
  color: '#36454F',
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
  transition: 'color 0.2s'
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
  maxWidth: '480px',

  boxShadow: `
    0 2px 6px rgba(0,0,0,0.08),        /* micro shadow */
    0 12px 24px rgba(0,0,0,0.12),      /* profondeur */
    0 22px 40px rgba(0,0,0,0.10),      /* soft drop */
    0 38px 32px -12px rgba(255,255,255,0.18) /* reflet miroir */
  `,

  background: 'rgba(255,255,255,0.9)',
  backdropFilter: 'blur(20px)',
  borderRadius: '24px',
  padding: '40px',
  border: '1px solid rgba(255,255,255,0.25)'
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

const socialButtonsStyle = {
  display: 'grid',
  gridTemplateColumns: 'repeat(3, 1fr)',
  gap: '12px',
  marginBottom: '24px'
}

const socialButtonStyle = {
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '8px',
  padding: '12px',
  background: '#b3c7c124',
  border: '1px solid rgba(255, 255, 255, 0.1)',
  borderRadius: '12px',
  color: 'black',
  fontSize: '14px',
  fontWeight: '500',
  cursor: 'pointer',
  transition: 'all 0.2s',
  '&:hover': {
    background: 'rgba(255, 255, 255, 0.08)',
    borderColor: 'rgba(255, 255, 255, 0.2)'
  }
}

const dividerStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '16px',
  margin: '24px 0',
  fontSize: '13px',
  color: '#36454F'
}

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
  color: 'rgba(255, 255, 255, 0.4)',
  strokeWidth: '2',
  pointerEvents: 'none'
}

const getInputStyle = (inputName) => ({
  width: '100%',
  padding: '12px 12px 12px 42px',
  background: '#09a3725c',
  border: focusedInput.value === inputName ? '1px solid #10b981' : '1px solid rgba(255, 255, 255, 0.1)',
  borderRadius: '12px',
  fontSize: '14px',
  color: '#36454F',
  transition: 'all 0.2s',
  outline: 'none',
  boxSizing: 'border-box',
  '&::placeholder': {
    color: 'rgba(255, 255, 255, 0.4)'
  }
})

const getPasswordInputStyle = computed(() => ({
  width: '100%',
  padding: '12px 42px 12px 42px',
  background: '#09a3725c',
  border: focusedInput.value === 'password' ? '1px solid #10b981' : '1px solid rgba(255, 255, 255, 0.1)',
  borderRadius: '12px',
  fontSize: '14px',
  color: '#36454F',
  transition: 'all 0.2s',
  outline: 'none',
  boxSizing: 'border-box'
}))

const togglePasswordStyle = {
  position: 'absolute',
  right: '12px',
  background: 'none',
  border: 'none',
  cursor: 'pointer',
  color: 'rgba(255, 255, 255, 0.4)',
  padding: '8px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  borderRadius: '8px',
  transition: 'all 0.2s',
  '&:hover': {
    color: '#3A3A3A'
  }
}

const passwordHintStyle = {
  fontSize: '12px',
  color: 'rgba(255, 255, 255, 0.4)',
  margin: 0
}

const submitButtonStyle = computed(() => ({
  width: '100%',
  padding: '14px',
  background: submitHovered.value && !loading.value
    ? 'linear-gradient(135deg, #059669 0%, #047857 100%)'
    : 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
  color: '#36454F',
  border: 'none',
  borderRadius: '12px',
  fontSize: '15px',
  fontWeight: '600',
  cursor: loading.value ? 'not-allowed' : 'pointer',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '10px',
  transition: 'all 0.3s',
  marginTop: '8px',
  opacity: loading.value ? 0.7 : 1,
  transform: submitHovered.value && !loading.value ? 'translateY(-1px)' : 'translateY(0)',
  boxShadow: submitHovered.value && !loading.value
    ? '0 8px 20px rgba(16, 185, 129, 0.4)'
    : '0 4px 12px rgba(16, 185, 129, 0.25)'
}))

const loadingSpinnerStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '10px'
}

const formFooterStyle = {
  marginTop: '24px',
  textAlign: 'center'
}

const footerTextStyle = {
  fontSize: '14px',
  color: '#3A3A3A',
  margin: 0
}

const linkStyle = {
  color: '#10b981',
  textDecoration: 'none',
  fontWeight: '600',
  transition: 'color 0.2s'
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

* {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeInDelay {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes float {
  0%, 100% {
    transform: translate(0, 0);
  }
  33% {
    transform: translate(30px, -30px);
  }
  66% {
    transform: translate(-20px, 20px);
  }
}

.fade-in {
  animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) backwards;
}

.fade-in-delay {
  animation: fadeInDelay 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s backwards;
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
  filter: blur(100px);
  opacity: 0.3;
  animation: float 20s ease-in-out infinite;
}

.orb-1 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(16, 185, 129, 0.4), transparent);
  top: -200px;
  left: -200px;
}

.orb-2 {
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(5, 150, 105, 0.3), transparent);
  bottom: -250px;
  right: -200px;
  animation-delay: 10s;
}

.orb-3 {
  width: 450px;
  height: 450px;
  background: radial-gradient(circle, rgba(16, 185, 129, 0.25), transparent);
  top: 50%;
  left: 50%;
  animation-delay: 5s;
}

.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top: 2px solid white;
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

@media (max-width: 968px) {
  div[style*="gridTemplateColumns"] {
    grid-template-columns: 1fr !important;
    gap: 40px !important;
  }
  
  div[style*="leftSideStyle"] {
    display: none !important;
  }
}

/* Styles pour les hover et pseudo-éléments */
a[style*="footerLinkStyle"]:hover {
  color: rgba(255, 255, 255, 0.8) !important;
}

button[style*="socialButtonStyle"]:hover {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: rgba(255, 255, 255, 0.2) !important;
}

div[style*="dividerStyle"]::before {
  content: "";
  flex: 1;
  height: 1px;
  background: rgba(255, 255, 255, 0.1);
}

div[style*="dividerStyle"]::after {
  content: "";
  flex: 1;
  height: 1px;
  background: rgba(255, 255, 255, 0.1);
}

button[style*="togglePasswordStyle"]:hover {
  color: #3A3A3A !important;
}

a[style*="linkStyle"]:hover {
  color: #059669 !important;
}

input::placeholder {
  color: rgba(255, 255, 255, 0.4);
}
</style>
