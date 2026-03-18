import os

def modernize_controllers(directory):
    facades = {
        'Schema': 'use Illuminate\\Support\\Facades\\Schema;',
        'DB': 'use Illuminate\\Support\\Facades\\DB;',
        'Auth': 'use Illuminate\\Support\\Facades\\Auth;',
        'Hash': 'use Illuminate\\Support\\Facades\\Hash;',
        'Validator': 'use Illuminate\\Support\\Facades\\Validator;',
        'Session': 'use Illuminate\\Support\\Facades\\Session;',
        'Response': 'use Illuminate\\Support\\Facades\\Response;',
        'File': 'use Illuminate\\Support\\Facades\\File;',
        'Str': 'use Illuminate\\Support\\Str;',
        'Config': 'use Illuminate\\Support\\Facades\\Config;',
        'URL': 'use Illuminate\\Support\\Facades\\URL;'
    }

    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith(".php"):
                path = os.path.join(root, file)
                with open(path, 'r') as f:
                    content = f.read()

                new_imports = []
                for name, statement in facades.items():
                    # Jika ada pemanggilan Class:: tapi belum ada 'use' statement-nya
                    if f"{name}::" in content and statement not in content:
                        new_imports.append(statement)

                if new_imports:
                    lines = content.splitlines()
                    # Cari baris namespace (biasanya baris ke-3 atau ke-5)
                    for i, line in enumerate(lines):
                        if line.startswith("namespace "):
                            for imp in new_imports:
                                lines.insert(i + 1, imp)
                            break
                    
                    with open(path, 'w') as f:
                        f.write("\n".join(lines))
                    print(f"Modernized: {file}")

modernize_controllers('app/Http/Controllers')
