<template>
  <SidebarLayout currentPage="utilisateurs">
    <!-- Elegant Header -->
    <header :style="headerStyle" class="fade-in">
      <div :style="headerContentStyle">
        <div :style="breadcrumbStyle">
          <span :style="breadcrumbItemStyle">Tableau de bord</span>
          <svg :style="breadcrumbArrowStyle" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
          <span :style="breadcrumbActiveStyle">Utilisateurs</span>
        </div>
        <h1 :style="titleStyle">Gestion des Utilisateurs</h1>
        <p :style="subtitleStyle">
          <svg :style="subtitleIconStyle" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          {{ filteredUsers.length }} utilisateur(s){{ activeFilter !== 'all' ? ' filtré(s)' : '' }}
        </p>
        
        <!-- Filtres -->
        <div :style="filtersContainerStyle">
          <button 
            :style="getFilterButtonStyle('all')"
            @click="activeFilter = 'all'"
          >
            Tous
          </button>
          <button 
            :style="getFilterButtonStyle('magasins')"
            @click="activeFilter = 'magasins'"
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Magasins
          </button>
          <button 
            :style="getFilterButtonStyle('entrepots')"
            @click="activeFilter = 'entrepots'"
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 8.35V20a2 2 0 01-2 2H4a2 2 0 01-2-2V8.35A2 2 0 013.26 6.5l8-3.2a2 2 0 011.48 0l8 3.2A2 2 0 0122 8.35z"/>
            </svg>
            Entrepôts
          </button>
          <button 
            :style="getFilterButtonStyle('clients')"
            @click="activeFilter = 'clients'"
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
            </svg>
            Clients
          </button>
          <button 
            :style="getFilterButtonStyle('fournisseurs')"
            @click="activeFilter = 'fournisseurs'"
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Fournisseurs
          </button>
        </div>
      </div>
      <button 
        :style="addButtonStyle" 
        @click="openCreateModal" 
        @mouseenter="addHovered = true" 
        @mouseleave="addHovered = false"
        class="fade-in"
        style="animation-delay: 0.2s"
      >
        <div :style="addButtonIconStyle">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
          </svg>
        </div>
        <span>Nouvel Utilisateur</span>
      </button>
    </header>

    <!-- Stats Overview -->
    <div :style="statsOverviewStyle" class="fade-in" style="animation-delay: 0.1s">
      <div :style="statCardStyle">
        <div :style="statIconWrapperStyle('#10b981')">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/>
          </svg>
        </div>
        <div>
          <div :style="statValueStyle">{{ stats.total }}</div>
          <div :style="statLabelStyle">Total Utilisateurs</div>
        </div>
      </div>
      
      <div :style="statCardStyle">
        <div :style="statIconWrapperStyle('#3b82f6')">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
          </svg>
        </div>
        <div>
          <div :style="statValueStyle">{{ stats.actifs }}</div>
          <div :style="statLabelStyle">Actifs</div>
        </div>
      </div>
      
      <div :style="statCardStyle">
        <div :style="statIconWrapperStyle('#8b5cf6')">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
            <circle cx="8.5" cy="7" r="4"/>
            <polyline points="17 11 19 13 23 9"/>
          </svg>
        </div>
        <div>
          <div :style="statValueStyle">{{ getRoleCount('admin') }}</div>
          <div :style="statLabelStyle">Administrateurs</div>
        </div>
      </div>
      
      <div :style="statCardStyle">
        <div :style="statIconWrapperStyle('#f59e0b')">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0110 0v4"/>
          </svg>
        </div>
        <div>
          <div :style="statValueStyle">{{ getUsersWithAccess() }}</div>
          <div :style="statLabelStyle">Avec accès</div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" :style="loadingStyle">
      <div :style="spinnerStyle"></div>
      <p>Chargement des utilisateurs...</p>
    </div>

    <!-- Error State -->
    <div v-if="error" :style="errorStyle">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <p>{{ error }}</p>
      <button :style="retryButtonStyle" @click="loadUsers">Réessayer</button>
    </div>

    <!-- Users Grid -->
    <div v-if="!loading && !error" :style="gridStyle">
      <div 
        v-for="(user, index) in filteredUsers" 
        :key="user.id"
        :style="getCardStyle(index)"
        @mouseenter="hoveredCard = index"
        @mouseleave="hoveredCard = null"
        class="card fade-in"
        :class="'delay-' + (index + 2)"
      >
        <!-- Card Glow Effect -->
        <div :style="getCardGlowStyle(index)"></div>
        
        <div :style="cardHeaderStyle">
          <div :style="getAvatarStyle(user, index)">
            {{ getInitials(user) }}
          </div>
          <div :style="cardActionsStyle">
            <button 
              :style="actionBtnStyle" 
              @click.stop="editUser(user)" 
              @mouseenter="hoveredAction = 'edit-' + index"
              @mouseleave="hoveredAction = null"
              title="Modifier"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
            </button>
            <button 
              :style="getDeleteBtnStyle(index)" 
              @click.stop="deleteUser(user.id)" 
              @mouseenter="hoveredAction = 'delete-' + index"
              @mouseleave="hoveredAction = null"
              title="Supprimer"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
              </svg>
            </button>
          </div>
        </div>
        
        <h3 :style="cardTitleStyle">{{ user.nom }} {{ user.prenom }}</h3>
        <p :style="cardEmailStyle">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          {{ user.email }}
        </p>
        
        <!-- Role Badge -->
        <div :style="getRoleBadgeStyle(user.role)">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l2.4 7.4h7.6l-6 4.6 2.3 7.4-6.3-4.6-6.3 4.6 2.3-7.4-6-4.6h7.6z"/>
          </svg>
          {{ getRoleLabel(user.role) }}
        </div>
        
        <!-- Access Info -->
        <div :style="accessContainerStyle">
          <div :style="accessItemStyle" v-if="user.acces.magasins === 'all' || (Array.isArray(user.acces.magasins) && user.acces.magasins.length > 0)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            <span>{{ formatAccess(user.acces.magasins, 'magasin') }}</span>
          </div>
          
          <div :style="accessItemStyle" v-if="user.acces.entrepots === 'all' || (Array.isArray(user.acces.entrepots) && user.acces.entrepots.length > 0)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 8.35V20a2 2 0 01-2 2H4a2 2 0 01-2-2V8.35A2 2 0 013.26 6.5l8-3.2a2 2 0 011.48 0l8 3.2A2 2 0 0122 8.35z"/>
              <path d="M6 18h12"/>
              <path d="M6 14h12"/>
            </svg>
            <span>{{ formatAccess(user.acces.entrepots, 'entrepôt') }}</span>
          </div>
          
          <div :style="accessItemStyle" v-if="user.acces.clients">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
            </svg>
            <span>Clients</span>
          </div>
          
          <div :style="accessItemStyle" v-if="user.acces.fournisseurs">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <span>Fournisseurs</span>
          </div>
          
          <!-- Badge si aucun accès -->
          <div v-if="!hasAnyAccess(user)" :style="noAccessBadgeStyle">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
            </svg>
            <span>Aucun accès défini</span>
          </div>
        </div>

        <div :style="cardFooterStyle">
          <button 
            :style="getStatusToggleStyle(user.actif, index)"
            @click.stop="toggleUserStatus(user.id)"
            @mouseenter="hoveredAction = 'status-' + index"
            @mouseleave="hoveredAction = null"
          >
            <span :style="statusDotStyle(user.actif)"></span>
            {{ user.actif ? 'Actif' : 'Inactif' }}
          </button>
          
          <span :style="lastConnexionStyle" v-if="user.derniere_connexion">
            Vu {{ formatDate(user.derniere_connexion) }}
          </span>
        </div>
      </div>
    </div>

    <!-- Premium Modal -->
    <Transition name="modal">
      <div v-if="showModal" :style="modalOverlayStyle" @click.self="closeModal">
        <div :style="modalStyle" class="modal-content">
          <!-- Modal Header -->
          <div :style="modalHeaderStyle">
            <div>
              <div :style="modalBadgeStyle">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
              <h2 :style="modalTitleStyle">{{ editingUser ? 'Modifier l\'Utilisateur' : 'Nouvel Utilisateur' }}</h2>
              <p :style="modalSubtitleStyle">{{ editingUser ? 'Mettez à jour les informations et les accès' : 'Créez un nouvel utilisateur et définissez ses accès' }}</p>
            </div>
            <button :style="closeButtonStyle" @click="closeModal">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
              </svg>
            </button>
          </div>
          
          <!-- Form -->
          <form @submit.prevent="saveUser" :style="formStyle">
            <!-- Informations personnelles -->
            <div :style="sectionTitleStyle">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              Informations personnelles
            </div>

            <div :style="formRowStyle">
              <div :style="formGroupStyle">
                <label :style="labelStyle">
                  Nom <span :style="requiredStyle">*</span>
                </label>
                <input 
                  v-model="formData.nom" 
                  type="text" 
                  :style="inputStyle"
                  placeholder="Dupont"
                  required
                />
              </div>
              
              <div :style="formGroupStyle">
                <label :style="labelStyle">
                  Prénom <span :style="requiredStyle">*</span>
                </label>
                <input 
                  v-model="formData.prenom" 
                  type="text" 
                  :style="inputStyle"
                  placeholder="Jean"
                  required
                />
              </div>
            </div>
            
            <div :style="formRowStyle">
              <div :style="formGroupStyle">
                <label :style="labelStyle">
                  Email <span :style="requiredStyle">*</span>
                </label>
                <input 
                  v-model="formData.email" 
                  type="email" 
                  :style="inputStyle"
                  placeholder="jean.dupont@example.com"
                  required
                />
              </div>
              
              <div :style="formGroupStyle">
                <label :style="labelStyle">Téléphone</label>
                <input 
                  v-model="formData.telephone" 
                  type="tel" 
                  :style="inputStyle"
                  placeholder="+33 1 23 45 67 89"
                />
              </div>
            </div>

            <div :style="formRowStyle">
              <div :style="formGroupStyle">
                <label :style="labelStyle">
                  Mot de passe {{ editingUser ? '' : '*' }}
                </label>
                <input 
                  v-model="formData.password" 
                  type="password" 
                  :style="inputStyle"
                  :placeholder="editingUser ? 'Laisser vide pour ne pas changer' : 'Minimum 6 caractères'"
                  :required="!editingUser"
                />
              </div>
              
              <div :style="formGroupStyle">
                <label :style="labelStyle">
                  Rôle <span :style="requiredStyle">*</span>
                </label>
                <select v-model="formData.role" :style="inputStyle" required>
                  <option value="vendeur">Vendeur</option>
                  <option value="manager">Manager</option>
                  <option value="admin">Administrateur</option>
                </select>
              </div>
            </div>
            
            <!-- Accès et permissions -->
            <div :style="sectionTitleStyle">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0110 0v4"/>
              </svg>
              Accès et permissions
            </div>

            <!-- Magasins -->
            <div :style="formGroupStyle">
              <label :style="labelStyle">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Accès aux magasins
              </label>
              <div :style="accessOptionsStyle">
                <label :style="checkboxLabelStyle">
                  <input 
                    type="checkbox" 
                    :checked="formData.acces_magasins === 'all'"
                    @change="toggleAllMagasins"
                    :style="checkboxStyle"
                  />
                  <span>Tous les magasins</span>
                </label>
              </div>
              <div :style="checkboxGridStyle" v-if="formData.acces_magasins !== 'all'">
                <label 
                  v-for="magasin in availableMagasins" 
                  :key="magasin.id"
                  :style="checkboxItemStyle"
                >
                  <input 
                    type="checkbox" 
                    :value="magasin.id"
                    v-model="formData.acces_magasins"
                    :style="checkboxStyle"
                  />
                  <span>{{ magasin.nom }}</span>
                </label>
              </div>
            </div>

            <!-- Entrepôts -->
            <div :style="formGroupStyle">
              <label :style="labelStyle">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 8.35V20a2 2 0 01-2 2H4a2 2 0 01-2-2V8.35A2 2 0 013.26 6.5l8-3.2a2 2 0 011.48 0l8 3.2A2 2 0 0122 8.35z"/>
                  <path d="M6 18h12"/>
                  <path d="M6 14h12"/>
                </svg>
                Accès aux entrepôts
              </label>
              <div :style="accessOptionsStyle">
                <label :style="checkboxLabelStyle">
                  <input 
                    type="checkbox" 
                    :checked="formData.acces_entrepots === 'all'"
                    @change="toggleAllEntrepots"
                    :style="checkboxStyle"
                  />
                  <span>Tous les entrepôts</span>
                </label>
              </div>
              <div :style="checkboxGridStyle" v-if="formData.acces_entrepots !== 'all'">
                <label 
                  v-for="entrepot in availableEntrepots" 
                  :key="entrepot.id"
                  :style="checkboxItemStyle"
                >
                  <input 
                    type="checkbox" 
                    :value="entrepot.id"
                    v-model="formData.acces_entrepots"
                    :style="checkboxStyle"
                  />
                  <div :style="entrepotLabelContainerStyle">
                    <span :style="entrepotNameStyle">{{ entrepot.nom }}</span>
                    <span :style="entrepotCodeStyle">{{ entrepot.code }}</span>
                  </div>
                </label>
              </div>
            </div>

            <!-- Autres accès -->
            <div :style="formRowStyle">
              <div :style="formGroupStyle">
                <label :style="checkboxLabelStyle">
                  <input type="checkbox" v-model="formData.acces_clients" :style="checkboxStyle" />
                  <span :style="checkboxTextStyle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                      <circle cx="9" cy="7" r="4"/>
                    </svg>
                    Accès Clients
                  </span>
                </label>
              </div>
              
              <div :style="formGroupStyle">
                <label :style="checkboxLabelStyle">
                  <input type="checkbox" v-model="formData.acces_fournisseurs" :style="checkboxStyle" />
                  <span :style="checkboxTextStyle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Accès Fournisseurs
                  </span>
                </label>
              </div>
            </div>

            <div :style="formGroupStyle">
              <label :style="checkboxLabelStyle">
                <input type="checkbox" v-model="formData.actif" :style="checkboxStyle" />
                <span :style="checkboxTextStyle">Compte actif</span>
              </label>
            </div>
            
            <div :style="formActionsStyle">
              <button type="button" :style="cancelButtonStyle" @click="closeModal">
                Annuler
              </button>
              <button type="submit" :style="submitButtonStyle" :disabled="saving">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                  <polyline points="17 21 17 13 7 13 7 21"/>
                  <polyline points="7 3 7 8 15 8"/>
                </svg>
                {{ saving ? 'Enregistrement...' : (editingUser ? 'Enregistrer' : 'Créer') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>

  </SidebarLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import SidebarLayout from './SidebarLayout.vue'

const API_BASE_URL = 'https://sogetrag.com/apistok'

const hoveredCard = ref(null)
const hoveredAction = ref(null)
const addHovered = ref(false)
const showModal = ref(false)
const editingUser = ref(null)
const loading = ref(false)
const saving = ref(false)
const error = ref(null)
const activeFilter = ref('all')

const users = ref([])
const availableMagasins = ref([])
const availableEntrepots = ref([])
const stats = ref({
  total: 0,
  actifs: 0,
  inactifs: 0,
  roles: []
})

const formData = reactive({
  nom: '',
  prenom: '',
  email: '',
  telephone: '',
  password: '',
  role: 'vendeur',
  acces_magasins: [],
  acces_entrepots: [],
  acces_clients: false,
  acces_fournisseurs: false,
  actif: true
})

// Computed
const filteredUsers = computed(() => {
  if (activeFilter.value === 'all') return users.value
  
  return users.value.filter(user => {
    switch (activeFilter.value) {
      case 'magasins':
        return user.acces.magasins === 'all' || (Array.isArray(user.acces.magasins) && user.acces.magasins.length > 0)
      case 'entrepots':
        return user.acces.entrepots === 'all' || (Array.isArray(user.acces.entrepots) && user.acces.entrepots.length > 0)
      case 'clients':
        return user.acces.clients === true
      case 'fournisseurs':
        return user.acces.fournisseurs === true
      default:
        return true
    }
  })
})
const getRoleCount = (role) => {
  const roleData = stats.value.roles.find(r => r.role === role)
  return roleData ? roleData.count : 0
}

const getUsersWithAccess = () => {
  return users.value.filter(user => hasAnyAccess(user)).length
}

// Methods
const getInitials = (user) => {
  return `${user.nom.charAt(0)}${user.prenom.charAt(0)}`.toUpperCase()
}

const getRoleLabel = (role) => {
  const labels = {
    'admin': 'Administrateur',
    'manager': 'Manager',
    'vendeur': 'Vendeur'
  }
  return labels[role] || role
}

const formatAccess = (access, type) => {
  if (access === 'all') return `Tous les ${type}s`
  if (Array.isArray(access)) return `${access.length} ${type}${access.length > 1 ? 's' : ''}`
  return ''
}

const hasAnyAccess = (user) => {
  const hasMagasins = user.acces.magasins === 'all' || (Array.isArray(user.acces.magasins) && user.acces.magasins.length > 0)
  const hasEntrepots = user.acces.entrepots === 'all' || (Array.isArray(user.acces.entrepots) && user.acces.entrepots.length > 0)
  const hasClients = user.acces.clients
  const hasFournisseurs = user.acces.fournisseurs
  
  return hasMagasins || hasEntrepots || hasClients || hasFournisseurs
}

const formatDate = (date) => {
  const d = new Date(date)
  const now = new Date()
  const diff = Math.floor((now - d) / 1000)
  
  if (diff < 60) return 'à l\'instant'
  if (diff < 3600) return `il y a ${Math.floor(diff / 60)}m`
  if (diff < 86400) return `il y a ${Math.floor(diff / 3600)}h`
  return `il y a ${Math.floor(diff / 86400)}j`
}

// API Functions
const loadUsers = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await fetch(`${API_BASE_URL}/api_users.php?action=list&_=${Math.random()}`)
    const data = await response.json()
    
    if (data.success) {
      users.value = data.data
    } else {
      error.value = data.error || 'Erreur lors du chargement des utilisateurs'
    }
  } catch (err) {
    error.value = 'Impossible de se connecter au serveur'
    console.error('Erreur:', err)
  } finally {
    loading.value = false
  }
}

const loadStats = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/api_users.php?action=stats&_=${Math.random()}`)
    const data = await response.json()
    
    if (data.success) {
      stats.value = data.data
    }
  } catch (err) {
    console.error('Erreur chargement stats:', err)
  }
}

const loadMagasins = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/api_users.php?action=magasins&_=${Math.random()}`)
    const data = await response.json()
    
    if (data.success) {
      availableMagasins.value = data.data
    }
  } catch (err) {
    console.error('Erreur chargement magasins:', err)
  }
}

const loadEntrepots = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/api_users.php?action=entrepots&_=${Math.random()}`)
    const data = await response.json()
    
    if (data.success) {
      availableEntrepots.value = data.data
    }
  } catch (err) {
    console.error('Erreur chargement entrepôts:', err)
  }
}

const saveUser = async () => {
  saving.value = true
  try {
    const url = editingUser.value 
      ? `${API_BASE_URL}/api_users.php?action=update`
      : `${API_BASE_URL}/api_users.php?action=add`
    
    const payload = editingUser.value
      ? { ...formData, id: editingUser.value.id }
      : { ...formData }

    const response = await fetch(url, {
      method: editingUser.value ? 'PUT' : 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
    
    const data = await response.json()
    
    if (data.success) {
      await loadUsers()
      await loadStats()
      closeModal()
    } else {
      alert(data.error || 'Erreur lors de l\'enregistrement')
    }
  } catch (err) {
    alert('Erreur de connexion au serveur')
    console.error('Erreur:', err)
  } finally {
    saving.value = false
  }
}

const deleteUser = async (id) => {
  if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) return
  
  try {
    const response = await fetch(`${API_BASE_URL}/api_users.php?action=delete&id=${id}`, {
      method: 'DELETE'
    })
    
    const data = await response.json()
    
    if (data.success) {
      await loadUsers()
      await loadStats()
    } else {
      alert(data.error || 'Erreur lors de la suppression')
    }
  } catch (err) {
    alert('Erreur de connexion au serveur')
    console.error('Erreur:', err)
  }
}

const toggleUserStatus = async (id) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api_users.php?action=toggle-status`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id })
    })
    
    const data = await response.json()
    
    if (data.success) {
      await loadUsers()
      await loadStats()
    } else {
      alert(data.error || 'Erreur lors du changement de statut')
    }
  } catch (err) {
    alert('Erreur de connexion au serveur')
    console.error('Erreur:', err)
  }
}

const openCreateModal = () => {
  editingUser.value = null
  resetForm()
  showModal.value = true
}

const editUser = (user) => {
  editingUser.value = user
  formData.nom = user.nom
  formData.prenom = user.prenom
  formData.email = user.email
  formData.telephone = user.telephone
  formData.password = ''
  formData.role = user.role
  formData.acces_magasins = user.acces.magasins === 'all' ? 'all' : (user.acces.magasins || [])
  formData.acces_entrepots = user.acces.entrepots === 'all' ? 'all' : (user.acces.entrepots || [])
  formData.acces_clients = user.acces.clients
  formData.acces_fournisseurs = user.acces.fournisseurs
  formData.actif = Boolean(user.actif)
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingUser.value = null
  resetForm()
}

const resetForm = () => {
  formData.nom = ''
  formData.prenom = ''
  formData.email = ''
  formData.telephone = ''
  formData.password = ''
  formData.role = 'vendeur'
  formData.acces_magasins = []
  formData.acces_entrepots = []
  formData.acces_clients = false
  formData.acces_fournisseurs = false
  formData.actif = true
}

const toggleAllMagasins = (e) => {
  formData.acces_magasins = e.target.checked ? 'all' : []
}

const toggleAllEntrepots = (e) => {
  formData.acces_entrepots = e.target.checked ? 'all' : []
}

// Load data on mount
onMounted(() => {
  loadUsers()
  loadStats()
  loadMagasins()
  loadEntrepots()
})

// Styles (similar to Magasins page)
const headerStyle = {
  display: 'flex',
  justifyContent: 'space-between',
  alignItems: 'flex-start',
  marginBottom: '32px',
  flexWrap: 'wrap',
  gap: '20px'
}

const headerContentStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '8px'
}

const breadcrumbStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '6px',
  marginBottom: '4px'
}

const breadcrumbItemStyle = {
  fontSize: '13px',
  color: '#94a3b8',
  fontWeight: '500'
}

const breadcrumbArrowStyle = {
  width: '14px',
  height: '14px',
  color: '#cbd5e1',
  strokeWidth: '2'
}

const breadcrumbActiveStyle = {
  fontSize: '13px',
  color: '#1e293b',
  fontWeight: '600'
}

const titleStyle = {
  fontSize: '32px',
  fontWeight: '700',
  color: '#0f172a',
  margin: '0',
  letterSpacing: '-0.02em'
}

const subtitleStyle = {
  fontSize: '14px',
  color: '#64748b',
  margin: '0',
  display: 'flex',
  alignItems: 'center',
  gap: '6px',
  fontWeight: '500'
}

const subtitleIconStyle = {
  width: '16px',
  height: '16px'
}

const filtersContainerStyle = {
  display: 'flex',
  flexWrap: 'wrap',
  gap: '8px',
  marginTop: '12px'
}

const getFilterButtonStyle = (filter) => ({
  display: 'flex',
  alignItems: 'center',
  gap: '6px',
  padding: '8px 16px',
  borderRadius: '10px',
  border: activeFilter.value === filter ? 'none' : '1px solid #e2e8f0',
  background: activeFilter.value === filter 
    ? 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'
    : 'white',
  color: activeFilter.value === filter ? 'white' : '#64748b',
  fontSize: '13px',
  fontWeight: '600',
  cursor: 'pointer',
  transition: 'all 0.2s',
  boxShadow: activeFilter.value === filter 
    ? '0 4px 12px rgba(139, 92, 246, 0.25)'
    : 'none'
})

const addButtonStyle = computed(() => ({
  display: 'flex',
  alignItems: 'center',
  gap: '10px',
  padding: '14px 24px',
  background: addHovered.value 
    ? 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)'
    : 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
  color: 'white',
  border: 'none',
  borderRadius: '12px',
  cursor: 'pointer',
  fontSize: '14px',
  fontWeight: '600',
  transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
  boxShadow: addHovered.value 
    ? '0 12px 28px rgba(139, 92, 246, 0.35)'
    : '0 4px 12px rgba(139, 92, 246, 0.25)',
  transform: addHovered.value ? 'translateY(-2px)' : 'translateY(0)',
  letterSpacing: '0.01em'
}))

const addButtonIconStyle = {
  width: '32px',
  height: '32px',
  background: 'rgba(255, 255, 255, 0.2)',
  borderRadius: '8px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center'
}

const statsOverviewStyle = {
  display: 'grid',
  gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
  gap: '16px',
  marginBottom: '32px'
}

const statCardStyle = {
  background: 'white',
  borderRadius: '16px',
  padding: '20px',
  display: 'flex',
  alignItems: 'center',
  gap: '16px',
  boxShadow: '0 1px 3px rgba(0, 0, 0, 0.05)',
  border: '1px solid #f1f5f9'
}

const statIconWrapperStyle = (color) => ({
  width: '48px',
  height: '48px',
  background: `${color}15`,
  borderRadius: '12px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  color: color,
  flexShrink: 0
})

const statValueStyle = {
  fontSize: '24px',
  fontWeight: '700',
  color: '#0f172a',
  lineHeight: '1.2',
  letterSpacing: '-0.01em'
}

const statLabelStyle = {
  fontSize: '13px',
  color: '#64748b',
  fontWeight: '500',
  marginTop: '2px'
}

const loadingStyle = {
  textAlign: 'center',
  padding: '60px 20px',
  color: '#64748b'
}

const spinnerStyle = {
  width: '48px',
  height: '48px',
  margin: '0 auto 16px',
  border: '4px solid #f1f5f9',
  borderTop: '4px solid #8b5cf6',
  borderRadius: '50%',
  animation: 'spin 1s linear infinite'
}

const errorStyle = {
  textAlign: 'center',
  padding: '60px 20px',
  color: '#ef4444'
}

const retryButtonStyle = {
  marginTop: '16px',
  padding: '12px 24px',
  background: '#8b5cf6',
  color: 'white',
  border: 'none',
  borderRadius: '8px',
  cursor: 'pointer',
  fontSize: '14px',
  fontWeight: '600'
}

const gridStyle = {
  display: 'grid',
  gridTemplateColumns: 'repeat(auto-fill, minmax(340px, 1fr))',
  gap: '20px'
}

const getCardStyle = (index) => ({
  backgroundColor: 'white',
  borderRadius: '20px',
  padding: '28px',
  boxShadow: hoveredCard.value === index 
    ? '0 20px 40px rgba(0, 0, 0, 0.1)' 
    : '0 2px 8px rgba(0, 0, 0, 0.06)',
  cursor: 'pointer',
  transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
  transform: hoveredCard.value === index ? 'translateY(-6px)' : 'translateY(0)',
  border: hoveredCard.value === index ? '1px solid #e2e8f0' : '1px solid #f1f5f9'
})

const getCardGlowStyle = (index) => ({
  position: 'absolute',
  inset: '-2px',
  background: 'linear-gradient(135deg, rgba(139, 92, 246, 0.2), transparent)',
  borderRadius: '20px',
  opacity: hoveredCard.value === index ? 1 : 0,
  transition: 'opacity 0.4s',
  filter: 'blur(12px)',
  zIndex: -1
})

const cardHeaderStyle = {
  display: 'flex',
  justifyContent: 'space-between',
  alignItems: 'flex-start',
  marginBottom: '20px'
}

const getAvatarStyle = (user, index) => {
  const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
  const color = colors[user.id % colors.length]
  
  return {
    width: '56px',
    height: '56px',
    borderRadius: '16px',
    background: hoveredCard.value === index 
      ? `linear-gradient(135deg, ${color} 0%, ${adjustColor(color, -15)} 100%)`
      : `${color}12`,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    fontSize: '18px',
    fontWeight: '700',
    color: hoveredCard.value === index ? 'white' : color,
    transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
    transform: hoveredCard.value === index ? 'rotate(-5deg) scale(1.05)' : 'rotate(0deg) scale(1)',
    boxShadow: hoveredCard.value === index ? `0 8px 20px ${color}40` : 'none'
  }
}

const cardActionsStyle = {
  display: 'flex',
  gap: '8px'
}

const actionBtnStyle = {
  width: '36px',
  height: '36px',
  borderRadius: '10px',
  border: '1px solid #e2e8f0',
  backgroundColor: 'white',
  cursor: 'pointer',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  color: '#64748b',
  transition: 'all 0.2s'
}

const getDeleteBtnStyle = (index) => ({
  ...actionBtnStyle,
  color: hoveredAction.value === 'delete-' + index ? '#ef4444' : '#64748b',
  backgroundColor: hoveredAction.value === 'delete-' + index ? '#fef2f2' : 'white',
  borderColor: hoveredAction.value === 'delete-' + index ? '#fecaca' : '#e2e8f0'
})

const cardTitleStyle = {
  fontSize: '20px',
  fontWeight: '700',
  color: '#0f172a',
  margin: '0 0 8px 0',
  letterSpacing: '-0.01em'
}

const cardEmailStyle = {
  fontSize: '14px',
  color: '#64748b',
  margin: '0 0 16px 0',
  display: 'flex',
  alignItems: 'center',
  gap: '6px',
  fontWeight: '500'
}

const getRoleBadgeStyle = (role) => {
  const colors = {
    'admin': { bg: '#fef3c7', text: '#92400e', border: '#fde68a' },
    'manager': { bg: '#dbeafe', text: '#1e40af', border: '#bfdbfe' },
    'vendeur': { bg: '#f3e8ff', text: '#6b21a8', border: '#e9d5ff' }
  }
  const color = colors[role] || colors.vendeur
  
  return {
    display: 'inline-flex',
    alignItems: 'center',
    gap: '6px',
    padding: '6px 14px',
    borderRadius: '20px',
    fontSize: '13px',
    fontWeight: '600',
    backgroundColor: color.bg,
    color: color.text,
    border: `1px solid ${color.border}`,
    marginBottom: '16px',
    letterSpacing: '0.01em'
  }
}

const accessContainerStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '8px',
  padding: '16px 0',
  borderTop: '1px solid #f1f5f9',
  borderBottom: '1px solid #f1f5f9',
  marginBottom: '16px'
}

const accessItemStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  fontSize: '13px',
  color: '#64748b',
  fontWeight: '500'
}

const noAccessBadgeStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  padding: '8px 12px',
  background: '#fef2f2',
  border: '1px solid #fecaca',
  borderRadius: '8px',
  fontSize: '12px',
  color: '#991b1b',
  fontWeight: '600',
  width: '100%',
  justifyContent: 'center'
}

const entrepotLabelContainerStyle = {
  display: 'flex',
  flexDirection: 'column',
  gap: '2px'
}

const entrepotNameStyle = {
  fontSize: '14px',
  fontWeight: '500',
  color: '#334155'
}

const entrepotCodeStyle = {
  fontSize: '11px',
  fontWeight: '500',
  color: '#94a3b8'
}

const cardFooterStyle = {
  display: 'flex',
  justifyContent: 'space-between',
  alignItems: 'center'
}

const getStatusToggleStyle = (actif, index) => ({
  display: 'inline-flex',
  alignItems: 'center',
  gap: '6px',
  padding: '6px 14px',
  borderRadius: '20px',
  fontSize: '13px',
  fontWeight: '600',
  backgroundColor: actif ? '#dcfce7' : '#fee2e2',
  color: actif ? '#166534' : '#991b1b',
  border: 'none',
  cursor: 'pointer',
  transition: 'all 0.2s',
  letterSpacing: '0.01em'
})

const statusDotStyle = (actif) => ({
  width: '6px',
  height: '6px',
  borderRadius: '50%',
  backgroundColor: actif ? '#22c55e' : '#ef4444'
})

const lastConnexionStyle = {
  fontSize: '12px',
  color: '#94a3b8',
  fontWeight: '500'
}

// Modal styles
const modalOverlayStyle = {
  position: 'fixed',
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  backgroundColor: 'rgba(15, 23, 42, 0.7)',
  backdropFilter: 'blur(8px)',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  zIndex: 1000,
  padding: '20px',
  overflowY: 'auto'
}

const modalStyle = {
  backgroundColor: 'white',
  borderRadius: '24px',
  width: '100%',
  maxWidth: '700px',
  maxHeight: '90vh',
  overflow: 'auto',
  boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)'
}

const modalHeaderStyle = {
  display: 'flex',
  justifyContent: 'space-between',
  alignItems: 'flex-start',
  padding: '32px 32px 24px',
  borderBottom: '1px solid #f1f5f9'
}

const modalBadgeStyle = {
  width: '40px',
  height: '40px',
  background: 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
  borderRadius: '12px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  color: 'white',
  marginBottom: '16px'
}

const modalTitleStyle = {
  fontSize: '24px',
  fontWeight: '700',
  color: '#0f172a',
  margin: '0 0 6px 0',
  letterSpacing: '-0.01em'
}

const modalSubtitleStyle = {
  fontSize: '14px',
  color: '#64748b',
  margin: 0,
  fontWeight: '500'
}

const closeButtonStyle = {
  background: 'none',
  border: 'none',
  cursor: 'pointer',
  color: '#94a3b8',
  padding: '8px',
  borderRadius: '8px',
  transition: 'all 0.2s'
}

const formStyle = {
  padding: '24px 32px 32px'
}

const sectionTitleStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  fontSize: '16px',
  fontWeight: '700',
  color: '#0f172a',
  marginBottom: '20px',
  marginTop: '24px',
  paddingBottom: '12px',
  borderBottom: '2px solid #f1f5f9'
}

const formGroupStyle = {
  marginBottom: '20px',
  flex: 1
}

const formRowStyle = {
  display: 'flex',
  gap: '16px'
}

const labelStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '6px',
  fontSize: '14px',
  fontWeight: '600',
  color: '#334155',
  marginBottom: '8px',
  letterSpacing: '0.01em'
}

const requiredStyle = {
  color: '#ef4444',
  marginLeft: '2px'
}

const inputStyle = {
  width: '100%',
  padding: '12px 16px',
  border: '1px solid #e2e8f0',
  borderRadius: '12px',
  fontSize: '14px',
  boxSizing: 'border-box',
  transition: 'all 0.2s',
  fontWeight: '500',
  color: '#1e293b'
}

const accessOptionsStyle = {
  marginBottom: '12px'
}

const checkboxLabelStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '10px',
  fontSize: '14px',
  color: '#334155',
  cursor: 'pointer',
  fontWeight: '500'
}

const checkboxStyle = {
  width: '20px',
  height: '20px',
  cursor: 'pointer',
  accentColor: '#8b5cf6'
}

const checkboxTextStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  fontSize: '14px',
  fontWeight: '600',
  color: '#334155'
}

const checkboxGridStyle = {
  display: 'grid',
  gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))',
  gap: '12px',
  padding: '16px',
  background: '#f8fafc',
  borderRadius: '12px'
}

const checkboxItemStyle = {
  display: 'flex',
  alignItems: 'center',
  gap: '8px',
  fontSize: '14px',
  color: '#475569',
  cursor: 'pointer',
  fontWeight: '500'
}

const formActionsStyle = {
  display: 'flex',
  gap: '12px',
  marginTop: '28px',
  paddingTop: '24px',
  borderTop: '1px solid #f1f5f9'
}

const cancelButtonStyle = {
  flex: 1,
  padding: '14px',
  backgroundColor: '#f8fafc',
  border: '1px solid #e2e8f0',
  borderRadius: '12px',
  fontSize: '14px',
  fontWeight: '600',
  color: '#64748b',
  cursor: 'pointer',
  transition: 'all 0.2s',
  letterSpacing: '0.01em'
}

const submitButtonStyle = {
  flex: 1,
  padding: '14px',
  background: 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
  border: 'none',
  borderRadius: '12px',
  fontSize: '14px',
  fontWeight: '600',
  color: 'white',
  cursor: 'pointer',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  gap: '8px',
  transition: 'all 0.2s',
  boxShadow: '0 4px 12px rgba(139, 92, 246, 0.25)',
  letterSpacing: '0.01em'
}

// Helper
const adjustColor = (color, amount) => {
  const num = parseInt(color.replace('#', ''), 16)
  const r = Math.max(0, Math.min(255, (num >> 16) + amount))
  const g = Math.max(0, Math.min(255, ((num >> 8) & 0x00FF) + amount))
  const b = Math.max(0, Math.min(255, (num & 0x0000FF) + amount))
  return '#' + ((r << 16) | (g << 8) | b).toString(16).padStart(6, '0')
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
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.fade-in {
  animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
}

.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }
.delay-5 { animation-delay: 0.5s; }

.card {
  position: relative;
  overflow: hidden;
}

.card::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.4) 50%, transparent 70%);
  transform: translateX(-100%) rotate(45deg);
  transition: transform 0.6s;
}

.card:hover::before {
  transform: translateX(100%) rotate(45deg);
}

.modal-enter-active, .modal-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-content,
.modal-leave-to .modal-content {
  transform: scale(0.95) translateY(20px);
}

.modal-content {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
</style>