<script setup lang="ts">
import type { AuthFormField } from '@nuxt/ui';
import { useAuths } from '~/composable/useAuths';


// // Since nuxt the auto import feature we import our auth function here
const { signIn } = useAuths()

// This is for SEO optimization (Relax i'll explain this)
useHead({
    title: 'Sign In - SAEA',
    meta: [
        {
            name: 'description', content: 'Sign Into the SAEA ERP'
        }
    ]
})

const fields = ref<AuthFormField[]>([
    {
        name: 'email',
        type: 'email',
        label: 'Email'
    },
    {
        name: 'password',
        type: 'password',
        label: 'Password'
    }
])

//Auth function
const handleLogin = async (data: any) => {
    try {
        const { error } = await signIn(data.email, data.password)
        if (error) throw error;
        // If successful, redirect to dashboard or home page
        console.log("User signed in:", data)
        navigateTo('/dashboard')
    } catch (error) {
        console.error("Error signing in:", error)
    }
}

</script>
<template>
    <div class="flex flex-col items-center justify-center gap-4 p-4">
        <UAuthForm title="Login" description="Enter your credentials to access your account." icon="i-lucide-user"
            :fields="fields" class="max-w-md" @submit="handleLogin" />
    </div>
</template>