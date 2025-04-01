import os
import re
import json

# CONFIG
ROUTES_DIR = '../routes'
CONTROLLERS_DIR = '../app/Http/Controllers'
OUTPUT_FILE = 'openapi.json'
API_VERSION = '3.0.0'

def get_route_files():
    """Get all .php route files"""
    return [os.path.join(ROUTES_DIR, f) for f in os.listdir(ROUTES_DIR) if f.endswith('.php')]

def parse_route_line(line, current_prefix=""):
    """Parse Laravel Route line"""
    pattern = r"Route::(get|post|put|delete|patch|options)\(\s*['\"](?P<uri>[^'\"]+)['\"]\s*,\s*\[(?P<controller>[^:]+)::class\s*,\s*'(?P<method>[^']+)'\]"
    match = re.search(pattern, line)
    if match:
        method = match.group(1).lower()
        uri = match.group("uri")
        controller = match.group("controller").replace('\\\\', '\\')
        action = match.group("method")

        # Combine with prefix if available
        full_path = "/" + "/".join(filter(None, [current_prefix.strip('/'), uri.strip('/')]))

        return {
            "method": method,
            "path": full_path.replace("//", "/"),
            "controller": controller,
            "action": action
        }
    return None

def find_controller_file(controller):
    """Search for the controller file recursively"""
    filename = controller.split("\\")[-1] + ".php"

    for root, dirs, files in os.walk(CONTROLLERS_DIR):
        if filename in files:
            return os.path.join(root, filename)

    print(f"⚠️ Controller file not found for: {controller}")
    return None

def escape_annotation(annotation):
    """Escape annotation for regex"""
    return annotation.replace("\\", r"\\")

def write_annotation(controller_file, method, path, http_method):
    """Write the @OA annotation if missing"""
    if not os.path.exists(controller_file):
        print(f"⚠️ Controller file not found: {controller_file}")
        return

    with open(controller_file, 'r', encoding='utf-8') as f:
        content = f.read()

    # check if annotation already exists
    if f"@OA\\{http_method.capitalize()}(" in content and path in content:
        return  # already documented

    annotation = f"""
    /**
     * @OA\\{http_method.capitalize()}(
     *     path="{path}",
     *     summary="Auto generated",
     *     tags={{"{os.path.basename(controller_file).replace('.php','')}" }},
     *     @OA\\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */
    """

    # safely inject annotation before method
    pattern = rf"(public function {method}\s*\()"
    annotation_safe = escape_annotation(annotation)
    new_content = re.sub(pattern, annotation_safe + r"\n    \1", content, flags=re.MULTILINE)

    with open(controller_file, 'w', encoding='utf-8') as f:
        f.write(new_content)

    print(f"✅ Annotation added to {controller_file}::{method}()")

def parse_routes():
    """Main"""
    openapi = {
        "openapi": API_VERSION,
        "info": {
            "title": "Laravel API Documentation",
            "version": "1.0.0"
        },
        "paths": {}
    }

    for file in get_route_files():
        print(f"📄 Scanning: {file}")
        current_prefix = ""

        with open(file, 'r', encoding='utf-8') as f:
            for line in f:
                # detect prefix
                prefix_match = re.search(r"prefix\s*\(\s*'([^']+)'", line)
                if prefix_match:
                    current_prefix = prefix_match.group(1)

                route_info = parse_route_line(line, current_prefix)
                if route_info:
                    method = route_info['method']
                    path = route_info['path']

                    if path not in openapi['paths']:
                        openapi['paths'][path] = {}

                    openapi['paths'][path][method] = {
                        "tags": [route_info["controller"]],
                        "summary": f"{route_info['controller']}::{route_info['action']}",
                        "responses": {
                            "200": {
                                "description": "Successful response"
                            }
                        }
                    }

                    # Write annotation
                    # print(route_info['controller'])
                    controller_file = find_controller_file(route_info['controller'])
                    if controller_file:
                        write_annotation(controller_file, route_info['action'], path, method)

    # Save openapi.json
    with open(OUTPUT_FILE, 'w', encoding='utf-8') as f:
        json.dump(openapi, f, indent=2)
        print(f"✅ {OUTPUT_FILE} generated")

if __name__ == "__main__":
    parse_routes()
