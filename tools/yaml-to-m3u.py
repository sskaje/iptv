import sys
import yaml


def print_m3u(yaml_file):

    objs = yaml.load(open(yaml_file, 'r'), Loader=yaml.SafeLoader)

    print(objs)
    print('#EXTM3U')
    for ch in objs['channels']:
        # todo: add filters
        print("#EXTINF:-1," + ch['title'] + "\n" + ch['uri'])


if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python yaml-to-m3u.py <yaml_file>")
        sys.exit(1)

    yaml_file = sys.argv[1]
    print_m3u(yaml_file)