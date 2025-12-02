<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
import { Select } from "@/components/ui/select";
</script>

<template>
    <AuthBase
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6 items-center w-full md:w-xl"
        >
            <div class="grid md:grid-cols-2 gap-4 md:gap-6 w-full">
                <div class="flex flex-col gap-4">
                    <div class="grid gap-2">
                        <Label for="name">Full name</Label>
                        <Input
                            id="name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="full name"
                            name="name"
                            placeholder="Full name"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            :tabindex="2"
                            autocomplete="email"
                            name="email"
                            placeholder="email@example.com"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="3"
                            autocomplete="new-password"
                            name="password"
                            placeholder="Password"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirm password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            name="password_confirmation"
                            placeholder="Confirm password"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="phone">Phone</Label>
                        <Input
                            id="phone"
                            type="text"
                            required
                            autofocus
                            :tabindex="11"
                            autocomplete="phone"
                            name="phone"
                            placeholder="Phone"
                        />
                        <InputError :message="errors.phone" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="taxNumber">Tax number</Label>
                        <Input
                            id="taxNumber"
                            type="text"
                            required
                            autofocus
                            :tabindex="12"
                            autocomplete="taxNumber"
                            name="taxNumber"
                            placeholder="Tax number"
                        />
                        <InputError :message="errors.taxNumber" />
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="grid gap-2">
                        <Label for="countryCode">Country</Label>
                        <Select
                            id="countryCode"
                            required
                            :tabindex="5"
                            autocomplete="country"
                            name="countryCode"
                            default-value="US"
                            :options="{
                            'US': 'United States'
                        }"
                        />
                        <InputError :message="errors.countryCode" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="regionCode">Region</Label>
                        <Select
                            id="regionCode"
                            required
                            :tabindex="6"
                            autocomplete="region"
                            name="regionCode"
                            default-value="US-NY"
                            :options="{
                            'US-NY': 'New York'
                        }"
                        />
                        <InputError :message="errors.regionCode" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="city">City</Label>
                        <Input
                            id="city"
                            type="text"
                            required
                            autofocus
                            :tabindex="7"
                            autocomplete="city"
                            name="city"
                            placeholder="City"
                        />
                        <InputError :message="errors.city" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="zip">Zip</Label>
                        <Input
                            id="zip"
                            type="text"
                            required
                            autofocus
                            :tabindex="8"
                            autocomplete="zip"
                            name="zip"
                            placeholder="Zip"
                        />
                        <InputError :message="errors.zip" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lineOne">Address line 1</Label>
                        <Input
                            id="lineOne"
                            type="text"
                            required
                            autofocus
                            :tabindex="9"
                            autocomplete="lineOne"
                            name="lineOne"
                            placeholder="Address line 1"
                        />
                        <InputError :message="errors.lineOne" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lineTwo">Address line 2</Label>
                        <Input
                            id="lineTwo"
                            type="text"
                            required
                            autofocus
                            :tabindex="10"
                            autocomplete="lineTwo"
                            name="lineTwo"
                            placeholder="Address line 2"
                        />
                        <InputError :message="errors.lineTwo" />
                    </div>
                </div>
            </div>

            <Button
                type="submit"
                class="mt-2 w-full cursor-pointer"
                tabindex="13"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                Create account
            </Button>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="6"
                    >Log in</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
