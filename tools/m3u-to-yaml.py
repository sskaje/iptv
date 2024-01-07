import sys
import m3u8
import yaml
from pathlib import Path

def parse_m3u8(m3u_file):
    with open(m3u_file, 'r') as f:
        m3u_content = f.read()

        data = m3u8.parse(m3u_content)

        return data


def convert_to_yaml(m3u_file, yaml_file):
    m3u_data = parse_m3u8(m3u_file)

    objs = []
    for seg in m3u_data['segments']:
        objs.append({
            'title': seg['title'],
            'uri': seg['uri']
        })

    with open(yaml_file, 'w', encoding='UTF-8') as f:
        yaml.dump({'name': Path(yaml_file).stem, 'channels': objs}, f, default_flow_style=False, allow_unicode=True, sort_keys=False)


if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python m3u-to-yaml.py <m3u_file> <yaml_file>")
        sys.exit(1)

    m3u_file = sys.argv[1]
    yaml_file = sys.argv[2]
    convert_to_yaml(m3u_file, yaml_file)