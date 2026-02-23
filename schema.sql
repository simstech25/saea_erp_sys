-- 1. Sections Table (e.g., Accounts, Stores, Tenders)
CREATE TABLE sections (
  id uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  name TEXT UNIQUE NOT NULL,
  description TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 2. Profiles (Linked to Supabase Auth)
CREATE TABLE profiles (
  id uuid REFERENCES auth.users ON DELETE CASCADE PRIMARY KEY,
  full_name TEXT,
  email TEXT, -- Storing email here makes UI queries much faster
  role TEXT CHECK (role IN ('super_admin', 'admin', 'user')) DEFAULT 'user',
  section_id uuid REFERENCES sections(id),
  must_change_password BOOLEAN DEFAULT TRUE,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 3. Inventory (Added section_id for isolation)
CREATE TABLE inventory (
  id uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  section_id uuid REFERENCES sections(id) ON DELETE CASCADE, -- Which dept owns this stock?
  name TEXT NOT NULL,
  sku TEXT UNIQUE,
  quantity INTEGER DEFAULT 0,
  unit_price DECIMAL(12, 2) NOT NULL,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 4. Quotations (Added section_id and approval metadata)
CREATE TABLE quotations (
  id uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  quotation_code TEXT UNIQUE NOT NULL, 
  section_id uuid REFERENCES sections(id) ON DELETE CASCADE, -- For departmental filtering
  client_name TEXT NOT NULL,
  issuer_id uuid REFERENCES profiles(id),
  approver_id uuid REFERENCES profiles(id),
  status TEXT DEFAULT 'pending' CHECK (status IN ('draft', 'pending', 'approved', 'rejected')),
  rejection_reason TEXT, -- Useful if a supervisor sends it back
  approved_at TIMESTAMP WITH TIME ZONE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 5. Quotation Items
CREATE TABLE quotation_items (
  id uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  quotation_id uuid REFERENCES quotations(id) ON DELETE CASCADE,
  inventory_id uuid REFERENCES inventory(id),
  quantity INTEGER NOT NULL,
  price_at_quote DECIMAL(12, 2) NOT NULL -- Snapshotted price
);