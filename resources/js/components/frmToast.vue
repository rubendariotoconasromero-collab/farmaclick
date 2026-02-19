<template>
  <div class="toast-container">
    <transition-group name="toast" tag="div">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="toast-item"
        :class="`toast-${toast.type}`"
        @mouseenter="pauseTimer(toast.id)"
        @mouseleave="resumeTimer(toast.id)"
      >
        <div class="toast-icon">
          <span v-if="toast.type === 'success'">✅</span>
          <span v-else-if="toast.type === 'error'">❌</span>
          <span v-else>ℹ️</span>
        </div>
        <div class="toast-content">
          <strong>{{ toast.title }}</strong>
          <p v-if="toast.text">{{ toast.text }}</p>
        </div>
        <div
          class="toast-progress"
          :style="{ animationDuration: toast.timeout + 'ms' }"
        ></div>
        <button class="toast-close" @click="removeToast(toast.id)">✕</button>
      </div>
    </transition-group>
  </div>
</template>

<script>
export default {
  name: 'Toast',
  data() {
    return {
      toasts: [],
      timers: {}
    };
  },
  methods: {
    showToast({ title, text = '', type = 'info', timeout = 3000 }) {
      const id = Date.now() + Math.random();
      this.toasts.push({ id, title, text, type, timeout });

      this.timers[id] = setTimeout(() => {
        this.removeToast(id);
      }, timeout);
    },

    removeToast(id) {
      clearTimeout(this.timers[id]);
      delete this.timers[id];
      this.toasts = this.toasts.filter(t => t.id !== id);
    },

    pauseTimer(id) {
      clearTimeout(this.timers[id]);
    },

    resumeTimer(id) {
      const toast = this.toasts.find(t => t.id === id);
      if (toast) {
        this.timers[id] = setTimeout(() => {
          this.removeToast(id);
        }, toast.timeout);
      }
    },

    // Métodos públicos para usar desde otros componentes
    success(title, text = '', timeout = 500) {
      this.showToast({ title, text, type: 'success', timeout });
    },
    error(title, text = '', timeout = 3000) {
      this.showToast({ title, text, type: 'error', timeout });
    },
    info(title, text = '', timeout = 3000) {
      this.showToast({ title, text, type: 'info', timeout });
    }
  }
};
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  pointer-events: none;
}

.toast-item {
  display: flex;
  align-items: center;
  min-height: 3rem;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  color: white;
  font-family: system-ui, sans-serif;
  font-size: 0.9rem;
  pointer-events: all;
  position: relative;
  max-width: 300px;
  animation-fill-mode: both;
}

.toast-success { background: #4caf50; }
.toast-error { background: #f44336; }
.toast-info { background: #2196f3; }

.toast-icon {
  margin-right: 0.75rem;
  font-size: 1.25rem;
}

.toast-content {
  flex: 1;
}

.toast-content p {
  margin: 0.25rem 0 0;
  font-size: 0.85rem;
  opacity: 0.9;
}

.toast-close {
  background: transparent;
  border: none;
  color: white;
  font-size: 1rem;
  cursor: pointer;
  padding: 0 0.25rem;
  margin-left: 0.5rem;
  opacity: 0.7;
}

.toast-close:hover {
  opacity: 1;
}

.toast-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 100%;
  background: rgba(255, 255, 255, 0.3);
  animation: progress 1s linear forwards;
}

@keyframes progress {
  from { transform: scaleX(0); transform-origin: left; }
  to { transform: scaleX(1); }
}

/* Animaciones de entrada/salida */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

/* Responsive */
@media (max-width: 480px) {
  .toast-container {
    top: 0.5rem;
    right: 0.5rem;
    left: 0.5rem;
    align-items: center;
  }

  .toast-item {
    max-width: none;
    width: calc(100% - 1rem);
  }
}
</style>