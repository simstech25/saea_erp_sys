export const useAuth = () => {
  const supabase = useSupabaseClient();

  // Sign in with email and password
  const signIn = async (email, password) => {
    try {
      const { data, error } = await supabase.auth.signInWithPassword({
        email,
        password,
      });
      if (error) throw error;

      return data;
    } catch (error) {
      console.error("Error siging in:", error);
      throw error;
    }
  };

  // Sign Out Logic
  const signOut = async () => {
    try {
      const { error } = await supabase.auth.signOut();
      if (error) throw error;

      await navigateTo("/");
    } catch (error) {
      console.error("Error sigining out:", error);
      throw error;
    }
  };

  // The return statement here makes are functions exported and usable externally
  return {
    signIn,
    signOut,
  };
};
