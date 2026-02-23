export default defineNuxtRouteMiddleware(async (to) => {
  const user = useSupabaseUser();
  const { data: profile } = await useSupabaseClient()
    .from("profiles")
    .select("role, section_id")
    .eq("id", user.value?.id)
    .single();

  // If trying to access /admin and not a super_admin or admin
  if (to.path.startsWith("/admin") && profile.role === "user") {
    return navigateTo("/");
  }

  if (
    to.path.startsWith("/stores") &&
    profile.section_name !== "Stores" &&
    profile.role !== "super_admin"
  ) {
    return navigateTo("/unauthorized");
  }
});
